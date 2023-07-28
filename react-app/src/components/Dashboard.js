import React from 'react';
import { useLocation } from 'react-router-dom';

const Dashboard = () => {
    const location = useLocation();
    const name = location.state ? location.state.name : '';

    return (
        <div>
            <h2>Welcome to the Dashboard, {name}!</h2>
            {/* Tutaj możesz dodać inne elementy interfejsu użytkownika dla panelu */}
        </div>
    );
};

export default Dashboard;
