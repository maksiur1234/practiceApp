import React, { useContext, useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { AuthContext } from '../auth-context';
import api from '../api';
import '../css/Dashboard.css';

const Dashboard = () => {
    const authContext = useContext(AuthContext);
    const [companies, setCompanies] = useState([]);

    useEffect(() => {
        const fetchCompanies = async () => {
            try {
                if (authContext.user?.id) {
                    const response = await api.get(`/api/companies?user_id=${authContext.user.id}`);
                    setCompanies(response.data.companies);
                }
            } catch (error) {
                console.error(error.response?.data || error.message);
            }
        };
        fetchCompanies();
    }, [authContext.user?.id]);

    return (
        <div>
            <div className="dashboard-navbar">
                <div className="dashboard-nav-links">
                    <Link to="/dashboard" className="dashboard-nav-link">Home</Link>
                    <Link to="/create-company" className="dashboard-nav-link">Create Company</Link>
                    <Link to="/create-event" className="dashboard-nav-link">Create Event</Link>
                    <Link to="/create-gift" className="dashboard-nav-link">Create Gift</Link>
                </div>
                <div className="dashboard-nav-links">
                    <Link to="/logout" className="dashboard-nav-link">Logout</Link>
                </div>
            </div>

            <div className="dashboard-content-container">
                <div className="dashboard-content">
                    <h2>
                        Welcome, {authContext.user ? authContext.user.name : 'Guest'}
                    </h2>
                    <h2>Your companies:</h2>
                    {Array.isArray(companies) && companies.length > 0 ? (
                        <ul>
                            {companies.map((company) => (
                                <li key={company.id}>{company.name}</li>
                            ))}
                        </ul>
                    ) : (
                        <p>No companies found.</p>
                    )}
                </div>
            </div>
        </div>
    );
};

export default Dashboard;
