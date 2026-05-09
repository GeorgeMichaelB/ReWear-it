import { useState, useEffect } from 'react';
import { commentsAPI } from '../services/api';

function CommentsPage() {
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState('');
  const [replyTo, setReplyTo] = useState(null);
  const [replyContent, setReplyContent] = useState('');
  const [loading, setLoading] = useState(false);
  const [itemId] = useState(1);

  useEffect(() => {
    fetchComments();
  }, [itemId]);

  const fetchComments = async () => {
    setLoading(true);
    try {
      const response = await commentsAPI.getComments(itemId);
      setComments(response.data.comments || []);
    } catch (error) {
      console.error('Error fetching comments:', error);
    }
    setLoading(false);
  };

  const addComment = async () => {
    if (!newComment.trim()) return;
    try {
      await commentsAPI.addComment({ item_id: itemId, content: newComment });
      setNewComment('');
      fetchComments();
    } catch (error) {
      console.error('Error adding comment:', error);
    }
  };

  const replyToComment = async (commentId) => {
    if (!replyContent.trim()) return;
    try {
      await commentsAPI.replyToComment(commentId, replyContent);
      setReplyTo(null);
      setReplyContent('');
      fetchComments();
    } catch (error) {
      console.error('Error replying to comment:', error);
    }
  };

  const likeComment = async (commentId) => {
    try {
      await commentsAPI.likeComment(commentId);
      fetchComments();
    } catch (error) {
      console.error('Error liking comment:', error);
    }
  };

  const renderComments = (commentList, depth = 0) => {
    return commentList.map((comment) => (
      <div key={comment.id} className="comment-thread" style={{ marginLeft: depth * 20 }}>
        <div className="comment-card">
          <div className="comment-header">
            <strong>{comment.user?.name || 'User'}</strong>
            <span className="timestamp">{comment.created_at}</span>
          </div>
          <p className="comment-content">{comment.content}</p>
          <div className="comment-actions">
            <button onClick={() => likeComment(comment.id)} className="btn-icon">
              👍 {comment.likes || 0}
            </button>
            {depth < 3 && (
              <button 
                onClick={() => setReplyTo(replyTo === comment.id ? null : comment.id)}
                className="btn-icon"
              >
                💬 Reply
              </button>
            )}
          </div>

          {replyTo === comment.id && (
            <div className="reply-form">
              <textarea
                value={replyContent}
                onChange={(e) => setReplyContent(e.target.value)}
                placeholder="Write a reply..."
                rows="2"
              />
              <button onClick={() => replyToComment(comment.id)} className="btn btn-primary btn-sm">
                Send Reply
              </button>
            </div>
          )}

          {comment.replies && comment.replies.length > 0 && renderComments(comment.replies, depth + 1)}
        </div>
      </div>
    ));
  };

  return (
    <div className="page-container">
      <h1>💬 Style Advice & Comments</h1>
      <p className="page-description">
        Nested comment threads for styling advice and upcycling techniques
      </p>

      <div className="card">
        <h3>Add a Comment</h3>
        <div className="form-group">
          <textarea
            value={newComment}
            onChange={(e) => setNewComment(e.target.value)}
            placeholder="Share styling advice or ask about upcycling techniques..."
            rows="3"
          />
        </div>
        <button onClick={addComment} className="btn btn-primary">
          Post Comment
        </button>
      </div>

      <div className="card">
        <h3>Comments ({comments.length})</h3>
        {loading && <p>Loading comments...</p>}
        
        {!loading && comments.length === 0 && (
          <p>No comments yet. Be the first to share your thoughts!</p>
        )}

        <div className="comments-section">
          {renderComments(comments)}
        </div>
      </div>

      <div className="card">
        <h3>Tips for Great Comments</h3>
        <ul>
          <li>Share styling tips for the item</li>
          <li>Ask about upcycling techniques</li>
          <li>Suggest complementary items</li>
          <li>Reply to others to build community</li>
        </ul>
      </div>
    </div>
  );
}

export default CommentsPage;