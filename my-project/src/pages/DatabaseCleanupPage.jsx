import { useState, useEffect } from 'react';
import { cleanupAPI } from '../services/api';

function DatabaseCleanupPage() {
  const [status, setStatus] = useState(null);
  const [health, setHealth] = useState(null);
  const [archived, setArchived] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchStatus();
    fetchHealth();
  }, []);

  const fetchStatus = async () => {
    setLoading(true);
    try {
      const response = await cleanupAPI.getStatus();
      setStatus(response.data);
    } catch (error) {
      console.error('Error fetching status:', error);
    }
    setLoading(false);
  };

  const fetchHealth = async () => {
    try {
      const response = await cleanupAPI.getHealth();
      setHealth(response.data);
    } catch (error) {
      console.error('Error fetching health:', error);
    }
  };

  const runCleanup = async (type = 'all') => {
    setLoading(true);
    try {
      const response = await cleanupAPI.runCleanup({ type });
      alert(`Cleanup completed! Archived ${response.data.results.transactions_archived} transactions`);
      fetchStatus();
    } catch (error) {
      console.error('Error running cleanup:', error);
    }
    setLoading(false);
  };

  const archiveTransactions = async () => {
    setLoading(true);
    try {
      const response = await cleanupAPI.archiveTransactions({ older_than_days: 90 });
      alert(`Archived ${response.data.archived_count} transactions`);
      fetchStatus();
    } catch (error) {
      console.error('Error archiving:', error);
    }
    setLoading(false);
  };

  const cleanupOrphaned = async () => {
    try {
      const response = await cleanupAPI.cleanupOrphaned();
      alert(`Cleaned up ${response.data.deleted_count} orphaned records`);
      fetchStatus();
    } catch (error) {
      console.error('Error cleaning orphaned:', error);
    }
  };

  const fetchArchived = async () => {
    try {
      const response = await cleanupAPI.getArchived();
      setArchived(response.data.archived_transactions || []);
    } catch (error) {
      console.error('Error fetching archived:', error);
    }
  };

  const restoreTransaction = async (transactionId) => {
    try {
      await cleanupAPI.restoreTransaction(transactionId);
      alert('Transaction restored!');
      fetchArchived();
    } catch (error) {
      console.error('Error restoring:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>🗄️ Database Cleanup & Archiving</h1>
      <p className="page-description">
        Automated cleanup and archiving of sold/completed transactions for database performance
      </p>

      {status && (
        <div className="card">
          <h3>Cleanup Status</h3>
          <div className="status-grid">
            <div className="status-item">
              <p className="status-label">Last Cleanup</p>
              <p className="status-value">{status.status.last_cleanup}</p>
            </div>
            <div className="status-item">
              <p className="status-label">Next Scheduled</p>
              <p className="status-value">{status.status.next_scheduled}</p>
            </div>
            <div className="status-item">
              <p className="status-label">Auto Cleanup</p>
              <p className="status-value">{status.status.auto_cleanup_enabled ? '✅ Enabled' : '❌ Disabled'}</p>
            </div>
            <div className="status-item">
              <p className="status-label">Retention</p>
              <p className="status-value">{status.status.retention_days} days</p>
            </div>
          </div>

          <div className="counts-grid">
            <div className="count-card">
              <span className="count">{status.counts.active_transactions}</span>
              <span className="count-label">Active</span>
            </div>
            <div className="count-card">
              <span className="count">{status.counts.completed_transactions}</span>
              <span className="count-label">Completed</span>
            </div>
            <div className="count-card">
              <span className="count">{status.counts.archived_transactions}</span>
              <span className="count-label">Archived</span>
            </div>
            <div className="count-card warning">
              <span className="count">{status.counts.orphaned_records}</span>
              <span className="count-label">Orphaned</span>
            </div>
          </div>
        </div>
      )}

      <div className="card">
        <h3>Cleanup Actions</h3>
        <div className="action-buttons">
          <button onClick={() => runCleanup('all')} disabled={loading} className="btn btn-primary">
            Run Full Cleanup
          </button>
          <button onClick={archiveTransactions} disabled={loading} className="btn btn-secondary">
            Archive Old Transactions
          </button>
          <button onClick={cleanupOrphaned} className="btn btn-warning">
            Clean Orphaned Records
          </button>
          <button onClick={fetchArchived} className="btn btn-secondary">
            View Archived
          </button>
        </div>
      </div>

      {archived.length > 0 && (
        <div className="card">
          <h3>Archived Transactions</h3>
          <table className="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Archived At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {archived.map((tx, i) => (
                <tr key={i}>
                  <td>{tx.id}</td>
                  <td>{tx.type}</td>
                  <td>{tx.amount || tx.value}</td>
                  <td>{tx.archived_at}</td>
                  <td>
                    <button onClick={() => restoreTransaction(tx.id)} className="btn btn-sm">
                      Restore
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {health && (
        <div className="card">
          <h3>Database Health</h3>
          <div className="health-grid">
            <div className="health-item">
              <p className="health-label">Database Size</p>
              <p className="health-value">{health.size_mb} MB</p>
            </div>
            <div className="health-item">
              <p className="health-label">Avg Query Time</p>
              <p className="health-value">{health.performance.avg_query_time_ms} ms</p>
            </div>
            <div className="health-item">
              <p className="health-label">Index Usage</p>
              <p className="health-value">{health.performance.index_usage}</p>
            </div>
            <div className="health-item">
              <p className="health-label">Last Optimized</p>
              <p className="health-value">{health.last_optimized}</p>
            </div>
          </div>

          <div className="table-counts">
            {Object.entries(health.table_counts).map(([table, count], i) => (
              <span key={i} className="table-count">
                {table}: {count}
              </span>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

export default DatabaseCleanupPage;