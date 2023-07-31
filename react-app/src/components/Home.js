import React from 'react';
import { Link } from 'react-router-dom';
import '../css/Home.css';

const Home = () => {
    return (
        <div className="container">
            <h2>Welcome</h2>
            <p>
                Please login if you want to use the app!
            </p>
            <div className="links">
                <Link to="/login">Login</Link>
                <Link to="/register">Register</Link>
            </div>
        </div>
    );
};

export default Home;
