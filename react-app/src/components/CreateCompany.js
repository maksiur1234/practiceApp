import React, { useState, useEffect, useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../api';
import '../css/CreateComp.css';
import { AuthContext } from '../auth-context';

const CreateCompany = () => {
    const navigate = useNavigate();
    const [types, setTypes] = useState([]);
    const authContext = useContext(AuthContext);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await api.get('/api/types');
                setTypes(response.data.types);
            } catch (error) {
                console.error(error.response.data);
            }
        };
        fetchData();
    }, []);

    const handleSubmit = async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);

        try {
            formData.append('user_id', authContext.user.id);
            const response = await api.post('/api/companies', formData);
            navigate('/dashboard');
        } catch (error) {
            if (error.response && error.response.data) {
                console.error(error.response.data);
            } else {
                console.error("Error.");
            }
        }
    };



    return (
        <div className="dashboard">
            <nav className="navbar">
                <div className="navbar__left">
                    <Link to="/dashboard" className="dashboard-nav-link">Home</Link>
                    <Link to="/create-company" className="dashboard-nav-link">Create Company</Link>
                    <Link to="/create-event" className="dashboard-nav-link">Create Event</Link>
                    <Link to="/create-gift" className="dashboard-nav-link">Create Gift</Link>
                </div>
                <div className="navbar__right">
                    <div className="navbar__item" onClick={() => navigate('/logout')}>Logout</div>
                </div>
            </nav>
            <div className="main-content">
                <div className="form-container">
                    <h2>Create Company</h2>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <label htmlFor="name" className="form-label">Company Name</label>
                            <input
                                type="text"
                                className="form-control"
                                id="name"
                                name="name"
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="email" className="form-label">Email Address</label>
                            <input
                                type="email"
                                className="form-control"
                                id="email"
                                name="email"
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="password" className="form-label">Password</label>
                            <input
                                type="password"
                                className="form-control"
                                id="password"
                                name="password"
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="confirmPassword" className="form-label">Confirm Password</label>
                            <input
                                type="password"
                                className="form-control"
                                id="confirmPassword"
                                name="confirmPassword"
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="type_id" className="form-label">Company Type</label>
                            <select
                                className="form-select"
                                id="type_id"
                                name="type_id"
                            >
                                <option value="">Select Company Type</option>
                                {types.map((type) => (
                                    <option key={type.id} value={type.id}>{type.name}</option>
                                ))}
                            </select>
                        </div>
                        <div className="row mb-0">
                            <div className="col-md-8 offset-md-4">
                                <button type="submit" className="btn btn-primary">Create Company</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default CreateCompany;
