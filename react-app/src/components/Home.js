import React from 'react';
import { Link } from 'react-router-dom';

const Home = () => {
    return (
        <div>
            <h2>Welcome to Practice App</h2>
            Please login if you want to use app!
            <p>
                To get started, please <Link to="/login">login</Link> or <Link to="/register">register</Link>.
            </p>
        </div>
    );
};

export default Home;
