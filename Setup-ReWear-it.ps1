# ReWear-it Docker Setup Script
# Run this script as Administrator on Windows

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "ReWear-it Docker Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "ERROR: Please run this script as Administrator" -ForegroundColor Red
    exit 1
}

# Step 1: Check if Docker is installed
Write-Host "[1/6] Checking if Docker is installed..." -ForegroundColor Yellow

$dockerPath = "C:\Program Files\Docker\Docker\Docker Desktop.exe"
if (Test-Path $dockerPath) {
    Write-Host "Docker is already installed." -ForegroundColor Green
    $startDocker = Read-Host "Do you want to start Docker Desktop? (Y/N)"
    if ($startDocker -eq "Y" -or $startDocker -eq "y") {
        Start-Process $dockerPath
        Write-Host "Waiting for Docker to start..."
        Start-Sleep -Seconds 20
    }
} else {
    Write-Host "Docker not found. Installing Docker Desktop..." -ForegroundColor Yellow
    
    # Download Docker Desktop
    $dockerUrl = "https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe"
    $installerPath = "$env:TEMP\DockerDesktopInstaller.exe"
    
    Write-Host "Downloading Docker Desktop..." -ForegroundColor Cyan
    try {
        Invoke-WebRequest -Uri $dockerUrl -OutFile $installerPath -UseBasicParsing
    } catch {
        Write-Host "Failed to download Docker. Please install manually from: https://www.docker.com/products/docker-desktop" -ForegroundColor Red
        Write-Host "Press Enter to exit..."
        Read-Host
        exit 1
    }
    
    # Install Docker Desktop silently
    Write-Host "Installing Docker Desktop..." -ForegroundColor Cyan
    Start-Process -FilePath $installerPath -ArgumentList "install --quiet --accept-license" -Wait
    
    Write-Host "Docker installed. Please start Docker Desktop manually." -ForegroundColor Green
    Write-Host "After starting Docker, run this script again." -ForegroundColor Cyan
    Write-Host "Press Enter to exit..."
    Read-Host
    exit 0
}

# Step 2: Check if Docker is running
Write-Host "[2/6] Checking if Docker is running..." -ForegroundColor Yellow

$dockerRunning = $false
$maxRetries = 30
$retryCount = 0

while (-not $dockerRunning -and $retryCount -lt $maxRetries) {
    try {
        $null = docker info 2>$null
        $dockerRunning = $true
    } catch {
        Write-Host "Waiting for Docker to start... ($($retryCount + 1)/$maxRetries)" -ForegroundColor Gray
        Start-Sleep -Seconds 2
        $retryCount++
    }
}

if (-not $dockerRunning) {
    Write-Host "ERROR: Docker is not running. Please start Docker Desktop and try again." -ForegroundColor Red
    exit 1
}

Write-Host "Docker is running." -ForegroundColor Green

# Step 3: Navigate to project directory
Write-Host "[3/6] Navigating to project directory..." -ForegroundColor Yellow

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectDir = $scriptDir

# Check if docker-compose.yml exists
if (-not (Test-Path "$projectDir\docker-compose.yml")) {
    Write-Host "ERROR: docker-compose.yml not found in $projectDir" -ForegroundColor Red
    Write-Host "Press Enter to exit..."
    Read-Host
    exit 1
}

Set-Location $projectDir
Write-Host "Project directory: $projectDir" -ForegroundColor Green

# Step 4: Build and start containers
Write-Host "[4/6] Building and starting containers..." -ForegroundColor Yellow

# Stop any existing containers
Write-Host "Stopping existing containers..." -ForegroundColor Gray
docker-compose down --remove-orphans 2>$null

# Build and start
Write-Host "Building containers (this may take a few minutes)..." -ForegroundColor Cyan
docker-compose build --no-cache

Write-Host "Starting services..." -ForegroundColor Cyan
docker-compose up -d

# Step 5: Wait for services to be ready
Write-Host "[5/6] Waiting for services to be ready..." -ForegroundColor Yellow

# Wait for MySQL
Write-Host "Waiting for MySQL..." -ForegroundColor Gray
$mysqlReady = $false
$retryCount = 0

while (-not $mysqlReady -and $retryCount -lt 30) {
    try {
        $result = docker-compose exec -T mysql mysqladmin ping -h localhost -u root -prootpassword 2>$null
        if ($LASTEXITCODE -eq 0) {
            $mysqlReady = $true
        }
    } catch {
        Start-Sleep -Seconds 2
        $retryCount++
    }
}

if (-not $mysqlReady) {
    Write-Host "WARNING: MySQL may not be ready yet. Checking backend..." -ForegroundColor Yellow
}

# Wait for backend
Write-Host "Waiting for backend API..." -ForegroundColor Gray
Start-Sleep -Seconds 10

# Check backend health
$backendReady = $false
$retryCount = 0
while (-not $backendReady -and $retryCount -lt 20) {
    try {
        $response = Invoke-WebRequest -Uri "http://localhost:8000/api/message" -UseBasicParsing -TimeoutSec 2
        if ($response.StatusCode -eq 200) {
            $backendReady = $true
        }
    } catch {
        Start-Sleep -Seconds 2
        $retryCount++
    }
}

# Step 6: Show status
Write-Host "[6/6] Setup complete!" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Services Status:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
docker-compose ps

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Access URLs:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Frontend:  http://localhost:3000" -ForegroundColor Green
Write-Host "Backend:   http://localhost:8000" -ForegroundColor Green
Write-Host "MySQL:     localhost:3306" -ForegroundColor Green
Write-Host "  - User:     rewearit" -ForegroundColor Gray
Write-Host "  - Pass:     rewearit123" -ForegroundColor Gray
Write-Host "  - DB:       rewearit" -ForegroundColor Gray

Write-Host ""
Write-Host "Test Accounts:" -ForegroundColor Cyan
Write-Host "  - buyer@rewearit.com / password123" -ForegroundColor Gray
Write-Host "  - seller@rewearit.com / password123" -ForegroundColor Gray
Write-Host "  - admin@rewearit.com / password123" -ForegroundColor Gray

Write-Host ""
Write-Host "To stop: docker-compose down" -ForegroundColor Yellow
Write-Host "To view logs: docker-compose logs -f" -ForegroundColor Yellow
Write-Host ""

Write-Host "Press Enter to exit..."
Read-Host