import { useState, useEffect } from 'react';
import { mentorshipAPI } from '../services/api';
import './MentorshipPage.css';

function MentorshipPage() {
  const [mentors, setMentors] = useState([]);
  const [activeMentorships, setActiveMentorships] = useState([]);
  const [showRequestForm, setShowRequestForm] = useState(false);
  const [showApplyForm, setShowApplyForm] = useState(false);
  const [requestData, setRequestData] = useState({
    skill_interest: 'upcycling',
    experience_level: 'beginner',
    goals: [],
  });
  const [applicationData, setApplicationData] = useState({
    expertise: [],
    years_experience: '',
    specialties: [],
  });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchMentors();
    fetchActiveMentorships();
  }, []);

  const fetchMentors = async () => {
    setLoading(true);
    try {
      const response = await mentorshipAPI.getMentorRecommendations({ skill: requestData.skill_interest });
      setMentors(response.data.mentors || []);
    } catch (error) {
      console.error('Error fetching mentors:', error);
    }
    setLoading(false);
  };

  const fetchActiveMentorships = async () => {
    try {
      const response = await mentorshipAPI.getActiveMentorships();
      setActiveMentorships(response.data.active_mentorships || []);
    } catch (error) {
      console.error('Error fetching mentorships:', error);
    }
  };

  const requestMentor = async () => {
    try {
      await mentorshipAPI.requestMentor(requestData);
      alert('Mentorship request submitted! We will match you soon.');
      setShowRequestForm(false);
    } catch (error) {
      console.error('Error requesting mentor:', error);
    }
  };

  const applyAsMentor = async () => {
    try {
      await mentorshipAPI.applyAsMentor({
        expertise: ['upcycling', 'refashion'],
        years_experience: 5,
        specialties: ['denim', 'embroidery'],
      });
      alert('Mentor application submitted! We will review within 3-5 days.');
      setShowApplyForm(false);
    } catch (error) {
      console.error('Error applying as mentor:', error);
    }
  };

  const requestMatch = async (mentorId) => {
    try {
      await mentorshipAPI.requestMatch({ mentor_id: mentorId });
      alert('Match request sent to mentor!');
    } catch (error) {
      console.error('Error requesting match:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>🎓 Mentorship Program</h1>
      <p className="page-description">
        Connect novice upcyclers with experienced creators through mentor-mentee pairing
      </p>

      {activeMentorships.length > 0 && (
        <div className="card">
          <h3>Your Active Mentorships</h3>
          {activeMentorships.map((m, i) => (
            <div key={i} className="mentorship-card">
              <p><strong>Mentor:</strong> {m.mentor.name}</p>
              <p><strong>Skill:</strong> {m.skill}</p>
              <p><strong>Sessions:</strong> {m.sessions_completed}</p>
              <button className="btn btn-primary">Schedule Session</button>
            </div>
          ))}
        </div>
      )}

      <div className="card">
        <div style={{ display: 'flex', gap: '1rem' }}>
          <button onClick={() => { setShowRequestForm(!showRequestForm); setShowApplyForm(false); }} className="btn btn-primary">
            Find a Mentor
          </button>
          <button onClick={() => { setShowApplyForm(!showApplyForm); setShowRequestForm(false); }} className="btn btn-secondary">
            Apply as Mentor
          </button>
        </div>
      </div>

      {showRequestForm && (
        <div className="card">
          <h3>Find Your Mentor</h3>
           <div className="form-group-base">
            <label>Skill Interest: </label>
            <select
              value={requestData.skill_interest}
              onChange={(e) => setRequestData({ ...requestData, skill_interest: e.target.value })}
            >
              <option value="upcycling">Upcycling</option>
              <option value="tie-dye">Tie-Dye</option>
              <option value="embroidery">Embroidery</option>
              <option value="mending">Mending</option>
              <option value="refashion">Refashion</option>
            </select>
          </div>
           <div className="form-group-base">
            <label>Experience Level: </label>
            <select
              value={requestData.experience_level}
              onChange={(e) => setRequestData({ ...requestData, experience_level: e.target.value })}
            >
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>
          <button onClick={fetchMentors} className="btn btn-primary">Find Mentors</button>
        </div>
      )}

      {showApplyForm && (
        <div className="card">
          <h3>Apply to be a Mentor</h3>
           <div className="form-group-base">
            <label>Years of Experience: </label>
            <input
              type="number"
              value={applicationData.years_experience}
              onChange={(e) => setApplicationData({ ...applicationData, years_experience: e.target.value })}
              placeholder="5"
            />
          </div>
          <button onClick={applyAsMentor} className="btn btn-primary">Submit Application</button>
        </div>
      )}

      <div className="card">
        <h3>Recommended Mentors</h3>
        {loading && <p>Loading mentors...</p>}
        
        {mentors.map((mentor, index) => (
          <div key={index} className="mentor-card">
            <h4>{mentor.name}</h4>
            <p><strong>Expertise:</strong> {mentor.expertise.join(', ')}</p>
            <p><strong>Experience:</strong> {mentor.years_experience} years</p>
            <p><strong>Rating:</strong> ⭐ {mentor.rating}</p>
            <p><strong>Mentees Helped:</strong> {mentor.mentees_helped}</p>
            <p><strong>Availability:</strong> {mentor.availability}</p>
            <button onClick={() => requestMatch(mentor.id)} className="btn btn-primary">
              Request Match
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}

export default MentorshipPage;