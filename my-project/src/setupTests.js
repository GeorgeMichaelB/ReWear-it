import { beforeAll, afterEach, afterAll, vi } from 'vitest';
import '@testing-library/jest-dom';

// Mock localStorage
const localStorageMock = {
  getItem: vi.fn(() => null),
  setItem: vi.fn(() => {}),
  removeItem: vi.fn(() => {}),
  clear: vi.fn(() => {}),
};
global.localStorage = localStorageMock;

// Mock import.meta.env
global.importMetaEnv = {
  VITE_API_URL: 'http://localhost:8000/api',
};
vi.stubGlobal('import', {
  meta: {
    env: {
      VITE_API_URL: 'http://localhost:8000/api',
    },
  },
});

// Mock window.location
vi.stubGlobal('location', {
  href: 'http://localhost:3000',
});

// Mock React (for newer React versions that don't require explicit import)
vi.mock('react', () => ({
  default: {
    useState: vi.fn(() => [null, vi.fn()]),
    useEffect: vi.fn((fn) => fn()),
    useContext: vi.fn(),
    useRef: vi.fn(() => ({ current: null })),
    useCallback: vi.fn((fn) => fn),
    useMemo: vi.fn((fn) => fn()),
    createElement: vi.fn(),
  },
  useState: vi.fn(() => [null, vi.fn()]),
  useEffect: vi.fn((fn) => fn()),
  useContext: vi.fn(),
  useRef: vi.fn(() => ({ current: null })),
  useCallback: vi.fn((fn) => fn),
  useMemo: vi.fn((fn) => fn()),
  createElement: vi.fn(),
  Fragment: 'Fragment',
}));

// Silence console errors in tests
beforeAll(() => {
  vi.spyOn(console, 'error').mockImplementation(() => {});
  vi.spyOn(console, 'warn').mockImplementation(() => {});
});

afterEach(() => {
  vi.clearAllMocks();
});

afterAll(() => {
  vi.restoreAllMocks();
});