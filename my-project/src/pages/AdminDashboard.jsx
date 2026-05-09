import { useState, useEffect } from 'react';
import { adminAPI, healthAPI } from '../services/api';

function AdminDashboard() {
  const [commissionModifiers, setCommissionModifiers] = useState([]);
  const [sustainabilityAudit, setSustainabilityAudit] = useState(null);
  const [systemHealth, setSystemHealth] = useState(null);
  const [roles, setRoles] = useState([]);
  const [activeTab, setActiveTab] = useState('commission');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchCommissionModifiers();
  }, []);

  const fetchCommissionModifiers = async () => {
    setLoading(true);
    try {
      const response = await adminAPI.getCommissionModifiers();
      setCommissionModifiers(response.data.active_modifiers || []);
    } catch (error) {
      console.error('Error fetching modifiers:', error);
    }
    setLoading(false);
  };

  const setCommission = async () => {
    try {
      await adminAPI.setCommissionModifier({
        category_id: 1,
        modifier_type: 'zero_fee',
        value: 0,
        start_date: new Date().toISOString().split('T')[0],
        end_date: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
        reason: 'Holiday promotion',
      });
      alert('Commission modifier applied!');
      fetchCommissionModifiers();
    } catch (error) {
      console.error('Error setting commission:', error);
    }
  };

  const checkFee = async () => {
    try {
      const response = await adminAPI.calculateEffectiveFee({ amount: 100, category_id: 1 });
      alert(`Effective fee: $${response.data.fee_amount}\nSavings: $${response.data.savings}`);
    } catch (error) {
      console.error('Error checking fee:', error);
    }
  };

  const getSustainabilityAudit = async () => {
    try {
      const response = await adminAPI.getSustainabilityAudit('30_days');
      setSustainabilityAudit(response.data);
    } catch (error) {
      console.error('Error fetching audit:', error);
    }
  };

  const getSystemHealth = async () => {
    try {
      const response = await healthAPI.getSystemHealth();
      setSystemHealth(response.data);
    } catch (error) {
      console.error('Error fetching health:', error);
    }
  };

  const getRoles = async () => {
    try {
      const response = await adminAPI.getRoles();
      setRoles(response.data.roles || []);
    } catch (error) {
      console.error('Error fetching roles:', error);
    }
  };

  const renderTab = (tab) => {
    switch (tab) {
      case 'commission':
        return (
          <div>
            <div className="card">
              <h3>Dynamic Commission Modifiers (UC-37)</h3>
              <button onClick={setCommission} className="btn btn-primary">
                Apply "Zero-Fee" Period
              </button>
              <button onClick={checkFee} className="btn btn-secondary" style={{ marginLeft: '10px' }}>
                Check Effective Fee
              </button>
            </div>

            {commissionModifiers.length > 0 && (
              <div className="card">
                <h4>Active Modifiers</h4>
                {commissionModifiers.map((mod, i) => (
                  <div key={i} className="modifier-card">
                    <p><strong>Category:</strong> {mod.category}</p>
                    <p><strong>Original:</strong> {mod.original_fee} → <strong>Modified:</strong> {mod.modified_fee}</p>
                    <p><strong>Ends:</strong> {mod.ends_at}</p>
                  </div>
                ))}
              </div>
            )}
          </div>
        );

      case 'sustainability':
        return (
          <div>
            <div className="card">
              <h3>Sustainability Audit Report (UC-38)</h3>
              <button onClick={getSustainabilityAudit} className="btn btn-primary">
                Generate Report
              </button>
            </div>

            {sustainabilityAudit && (
              <div className="card">
                <h4>Platform Impact - {sustainabilityAudit.period}</h4>
                <div className="impact-grid">
                  <div className="impact-stat">
                    <span className="impact-value">{sustainabilityAudit.total_impact.waste_diverted_kg} kg</span>
                    <span className="impact-label">Waste Diverted</span>
                  </div>
                  <div className="impact-stat">
                    <span className="impact-value">{sustainabilityAudit.total_impact.co2_saved_kg} kg</span>
                    <span className="impact-label">CO₂ Saved</span>
                  </div>
                  <div className="impact-stat">
                    <span className="impact-value">{sustainabilityAudit.total_impact.water_saved_liters} L</span>
                    <span className="impact-label">Water Saved</span>
                  </div>
                  <div className="impact-stat">
                    <span className="impact-value">{sustainabilityAudit.total_impact.items_upcycled}</span>
                    <span className="impact-label">Items Upcycled</span>
                  </div>
                </div>
              </div>
            )}
          </div>
        );

      case 'rbac':
        return (
          <div>
            <div className="card">
              <h3>Role-Based Access Control (UC-39)</h3>
              <button onClick={getRoles} className="btn btn-secondary">Load Roles</button>
            </div>

            {roles.length > 0 && (
              <div className="card">
                <h4>Available Roles</h4>
                {roles.map((role, i) => (
                  <div key={i} className="role-card">
                    <p><strong>{role.name}</strong> - {role.description}</p>
                    <p>Permissions: {role.permissions.join(', ')}</p>
                  </div>
                ))}
              </div>
            )}
          </div>
        );

      case 'health':
        return (
          <div>
            <div className="card">
              <h3>System Health Monitoring (UC-40)</h3>
              <button onClick={getSystemHealth} className="btn btn-primary">
                Check System Status
              </button>
            </div>

            {systemHealth && (
              <div>
                <div className={`health-status ${systemHealth.status}`}>
                  <h3>Status: {systemHealth.status.toUpperCase()}</h3>
                  <p>Last checked: {systemHealth.timestamp}</p>
                </div>

                <div className="card">
                  <h4>Key Metrics</h4>
                  <div className="metrics-grid">
                    {Object.entries(systemHealth.metrics).map(([key, value], i) => (
                      <div key={i} className="metric-card">
                        <p className="metric-value">{value}</p>
                        <p className="metric-label">{key.replace(/_/g, ' ')}</p>
                      </div>
                    ))}
                  </div>
                </div>

                <div className="card">
                  <h4>Services</h4>
                  {systemHealth.services.map((service, i) => (
                    <div key={i} className={`service-card ${service.status}`}>
                      <span>{service.name}</span>
                      <span className={`status ${service.status}`}>{service.status}</span>
                      <span>{service.uptime}</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        );

      default:
        return null;
    }
  };

  return (
    <div className="page-container">
      <h1>⚙️ Admin Dashboard</h1>
      <p className="page-description">
        Platform administration - commissions, sustainability, RBAC, and system health
      </p>

      <div className="tabs">
        <button onClick={() => setActiveTab('commission')} className={activeTab === 'commission' ? 'active' : ''}>
          Commission (UC-37)
        </button>
        <button onClick={() => setActiveTab('sustainability')} className={activeTab === 'sustainability' ? 'active' : ''}>
          Sustainability (UC-38)
        </button>
        <button onClick={() => setActiveTab('rbac')} className={activeTab === 'rbac' ? 'active' : ''}>
          RBAC (UC-39)
        </button>
        <button onClick={() => setActiveTab('health')} className={activeTab === 'health' ? 'active' : ''}>
          Health (UC-40)
        </button>
      </div>

      {loading && <p>Loading...</p>}
      {renderTab(activeTab)}
    </div>
  );
}

export default AdminDashboard;