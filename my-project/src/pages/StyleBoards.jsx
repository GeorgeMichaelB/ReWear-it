import { useState, useEffect } from 'react';
import { styleBoardAPI } from '../services/api';

function StyleBoards() {
  const [publicBoards, setPublicBoards] = useState([]);
  const [followedBoards, setFollowedBoards] = useState([]);
  const [selectedBoard, setSelectedBoard] = useState(null);
  const [showCreateForm, setShowCreateForm] = useState(false);
  const [newBoard, setNewBoard] = useState({ name: '', description: '', is_public: true });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchPublicBoards();
    fetchFollowedBoards();
  }, []);

  const fetchPublicBoards = async () => {
    setLoading(true);
    try {
      const response = await styleBoardAPI.getPublicBoards();
      setPublicBoards(response.data.boards || []);
    } catch (error) {
      console.error('Error fetching public boards:', error);
    }
    setLoading(false);
  };

  const fetchFollowedBoards = async () => {
    try {
      const response = await styleBoardAPI.getFollowedBoards();
      setFollowedBoards(response.data.boards || []);
    } catch (error) {
      console.error('Error fetching followed boards:', error);
    }
  };

  const fetchBoardDetails = async (boardId) => {
    try {
      const response = await styleBoardAPI.getBoardDetails(boardId);
      setSelectedBoard(response.data);
    } catch (error) {
      console.error('Error fetching board details:', error);
    }
  };

  const createBoard = async () => {
    try {
      await styleBoardAPI.createBoard(newBoard);
      alert('Style board created!');
      setShowCreateForm(false);
      setNewBoard({ name: '', description: '', is_public: true });
      fetchPublicBoards();
    } catch (error) {
      console.error('Error creating board:', error);
    }
  };

  const followBoard = async (boardId) => {
    try {
      await styleBoardAPI.followBoard(boardId);
      alert('You are now following this board!');
      fetchFollowedBoards();
    } catch (error) {
      console.error('Error following board:', error);
    }
  };

  const deleteBoard = async (boardId) => {
    if (!confirm('Are you sure you want to delete this board?')) return;
    try {
      await styleBoardAPI.deleteBoard(boardId);
      alert('Board deleted!');
      setSelectedBoard(null);
      fetchPublicBoards();
    } catch (error) {
      console.error('Error deleting board:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>Style Boards</h1>
      <p className="page-description">
        Curate and follow collaborative public style boards - let others discover your fashion vision!
      </p>

      <div style={{ display: 'flex', gap: '1rem', marginBottom: '1rem' }}>
        <button onClick={() => setShowCreateForm(!showCreateForm)} className="btn btn-primary">
          {showCreateForm ? 'Cancel' : '+ Create Style Board'}
        </button>
      </div>

      {showCreateForm && (
        <div className="card">
          <h3>Create New Style Board</h3>
          <div className="form-group">
            <label>Name: </label>
            <input
              type="text"
              value={newBoard.name}
              onChange={(e) => setNewBoard({ ...newBoard, name: e.target.value })}
              placeholder="My Summer Collection"
            />
          </div>
          <div className="form-group">
            <label>Description: </label>
            <textarea
              value={newBoard.description}
              onChange={(e) => setNewBoard({ ...newBoard, description: e.target.value })}
              placeholder="Describe your style board..."
            />
          </div>
          <div className="form-group">
            <label>
              <input
                type="checkbox"
                checked={newBoard.is_public}
                onChange={(e) => setNewBoard({ ...newBoard, is_public: e.target.checked })}
              />
              Make Public (others can follow)
            </label>
          </div>
          <button onClick={createBoard} className="btn btn-primary">Create Board</button>
        </div>
      )}

      {followedBoards.length > 0 && (
        <div className="card">
          <h3>Your Followed Boards</h3>
          <div className="item-grid">
            {followedBoards.map((board, index) => (
              <div key={index} className="item-card">
                <h4>{board.name}</h4>
                <p>Followers: {board.follower_count}</p>
                <button onClick={() => fetchBoardDetails(board.id)} className="btn btn-secondary">
                  View
                </button>
              </div>
            ))}
          </div>
        </div>
      )}

      <div className="card">
        <h3>Discover Public Style Boards</h3>
        {loading && <p>Loading boards...</p>}
        <div className="item-grid">
          {publicBoards.map((board, index) => (
            <div key={index} className="item-card" onClick={() => fetchBoardDetails(board.id)}>
              <h4>{board.name}</h4>
              <p className="board-description">{board.description}</p>
              <div className="board-tags">
                {board.tags?.map((tag, i) => (
                  <span key={i} className="tag">{tag}</span>
                ))}
              </div>
              <p className="board-meta">
                By {board.creator} • {board.item_count} items • {board.follower_count} followers
              </p>
              <button onClick={(e) => { e.stopPropagation(); followBoard(board.id); }} className="btn btn-primary">
                Follow
              </button>
            </div>
          ))}
        </div>
      </div>

      {selectedBoard && (
        <div className="card highlight-card">
          <div style={{ display: 'flex', justifyContent: 'space-between' }}>
            <h3>{selectedBoard.board.name}</h3>
            <button onClick={() => setSelectedBoard(null)} className="btn btn-secondary">Close</button>
          </div>
          
          <p><strong>Creator:</strong> {selectedBoard.board.creator?.name}</p>
          <p><strong>Description:</strong> {selectedBoard.board.description}</p>
          <p><strong>Followers:</strong> {selectedBoard.board.follower_count}</p>
          <p><strong>Collaborators:</strong> {selectedBoard.board.collaborators?.map(c => c.name).join(', ')}</p>

          {selectedBoard.items && (
            <div className="dispute-section">
              <h4>Items in this Board</h4>
              <div className="item-grid">
                {selectedBoard.items.map((item, i) => (
                  <div key={i} className="item-card">
                    <h5>{item.name}</h5>
                    <p>${item.price}</p>
                    <button className="btn btn-secondary">View Item</button>
                  </div>
                ))}
              </div>
            </div>
          )}

          {selectedBoard.comments && (
            <div className="dispute-section">
              <h4>Comments</h4>
              {selectedBoard.comments.map((comment, i) => (
                <div key={i} className="comment">
                  <strong>{comment.user}:</strong> {comment.comment}
                  <span className="timestamp">{comment.timestamp}</span>
                </div>
              ))}
            </div>
          )}

          <div style={{ marginTop: '1rem' }}>
            <button onClick={() => followBoard(selectedBoard.board.id)} className="btn btn-primary">
              Follow This Board
            </button>
            <button onClick={() => deleteBoard(selectedBoard.board.id)} className="btn btn-danger" style={{ marginLeft: '10px' }}>
              Delete (if owner)
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

export default StyleBoards;