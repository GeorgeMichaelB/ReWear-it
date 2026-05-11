import { useState, useEffect } from 'react';
import { reportsAPI } from '../services/api';

function ReportsPage() {
  const [reports, setReports] = useState([]);
  const [selectedReport, setSelectedReport] = useState(null);
  const [showCreateForm, setShowCreateForm] = useState(false);
  const [newReport, setNewReport] = useState({
    report_type: 'harassment',
    target_type: 'user',
    target_id: '',
    description: '',
  });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchReports();
  }, []);

  const fetchReports = async () => {
    setLoading(true);
    try {
      const response = await reportsAPI.getAllReports();
      setReports(response.data.reports || []);
    } catch (error) {
      console.error('Error fetching reports:', error);
    }
    setLoading(false);
  };

  const createReport = async () => {
    try {
      await reportsAPI.createReport(newReport);
      alert('Report submitted successfully!');
      setShowCreateForm(false);
      setNewReport({ report_type: 'harassment', target_type: 'user', target_id: '', description: '' });
      fetchReports();
    } catch (error) {
      console.error('Error creating report:', error);
    }
  };

  const fetchReportDetails = async (reportId) => {
    try {
      const response = await reportsAPI.getReportDetails(reportId);
      setSelectedReport(response.data);
    } catch (error) {
      console.error('Error fetching report details:', error);
    }
  };

  const escalateReport = async (reportId) => {
    try {
      const response = await reportsAPI.escalateReport(reportId);
      alert('Report escalated to next stage!');
      fetchReportDetails(reportId);
    } catch (error) {
      console.error('Error escalating report:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>🚩 Content Reporting System</h1>
      <p className="page-description">
        Multi-stage reporting logic for counterfeit goods or harassment with shadow-bans
      </p>

      <div className="card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h3>All Reports</h3>
          <button onClick={() => setShowCreateForm(!showCreateForm)} className="btn btn-primary">
            {showCreateForm ? 'Cancel' : '+ New Report'}
          </button>
        </div>

        {loading && <p>Loading reports...</p>}

        {reports.map((report, index) => (
          <div 
            key={index} 
            className="report-item"
            onClick={() => fetchReportDetails(report.id)}
          >
            <p><strong>ID:</strong> {report.id}</p>
            <p><strong>Type:</strong> {report.type}</p>
            <span className={`severity-badge ${report.severity}`}>{report.severity}</span>
            <span className={`status-badge ${report.status}`}>{report.status}</span>
          </div>
        ))}
      </div>

      {showCreateForm && (
        <div className="card">
          <h3>Submit New Report</h3>
           <div className="form-group-base">
            <label>Report Type: </label>
            <select
              value={newReport.report_type}
              onChange={(e) => setNewReport({ ...newReport, report_type: e.target.value })}
            >
              <option value="counterfeit">Counterfeit Goods</option>
              <option value="harassment">Harassment</option>
              <option value="prohibited">Prohibited Item</option>
              <option value="scam">Scam</option>
              <option value="other">Other</option>
            </select>
          </div>
           <div className="form-group-base">
            <label>Target Type: </label>
            <select
              value={newReport.target_type}
              onChange={(e) => setNewReport({ ...newReport, target_type: e.target.value })}
            >
              <option value="user">User</option>
              <option value="item">Item</option>
              <option value="comment">Comment</option>
              <option value="message">Message</option>
            </select>
          </div>
           <div className="form-group-base">
            <label>Target ID: </label>
            <input
              type="text"
              value={newReport.target_id}
              onChange={(e) => setNewReport({ ...newReport, target_id: e.target.value })}
              placeholder="Enter ID"
            />
          </div>
           <div className="form-group-base">
            <label>Description: </label>
            <textarea
              value={newReport.description}
              onChange={(e) => setNewReport({ ...newReport, description: e.target.value })}
              rows="4"
              placeholder="Describe the issue in detail..."
            />
          </div>
          <button onClick={createReport} className="btn btn-primary">Submit Report</button>
        </div>
      )}

      {selectedReport && (
        <div className="card highlight-card">
          <div style={{ display: 'flex', justifyContent: 'space-between' }}>
            <h3>Report Details: {selectedReport.report.id}</h3>
            <button onClick={() => setSelectedReport(null)} className="btn btn-secondary">Close</button>
          </div>

          <div className="report-section">
            <p><strong>Type:</strong> {selectedReport.report.type}</p>
            <p><strong>Target:</strong> {selectedReport.report.target_type} #{selectedReport.report.target_id}</p>
            <p><strong>Description:</strong> {selectedReport.report.description}</p>
            <p><strong>Status:</strong> {selectedReport.report.status}</p>
            <p><strong>Stage:</strong> {selectedReport.report.stage}</p>
          </div>

          <div className="report-section">
            <h4>Timeline</h4>
            {selectedReport.timeline?.map((event, i) => (
              <p key={i}>Stage {event.stage}: {event.status} - {event.timestamp}</p>
            ))}
          </div>

          <div className="report-section">
            <h4>Admin Actions</h4>
            <button onClick={() => escalateReport(selectedReport.report.id)} className="btn btn-warning">
              Escalate Report
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default ReportsPage;