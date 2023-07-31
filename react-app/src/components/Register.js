import React, { useState } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';
import api from '../api';
import '../css/Register.css';
const Register = () => {
    const navigate = useNavigate();

    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await api.post('/register', formData);
            const user = response.data.user;
            console.log('User:', user);
            navigate('/dashboard', { state: { name: user.name } });
        } catch (error) {
            console.error(error.response.data);
        }
    };


    return (
        <div className="register-form">
            <h2>Register</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Name:</label>
                    <input type="text" name="name" value={formData.name} onChange={handleChange} />
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" value={formData.email} onChange={handleChange} />
                </div>
                <div>
                    <label>Password:</label>
                    <input type="password" name="password" value={formData.password} onChange={handleChange} />
                </div>
                <div>
                    <label>Confirm Password:</label>
                    <input type="password" name="password_confirmation" value={formData.password_confirmation} onChange={handleChange} />
                </div>
                <div>
                    <button type="submit">Register</button>
                </div>
            </form>
            <p className="login-link">
                Already have an account? <Link to="/login">Login here</Link>
            </p>
        </div>
    );
};

export default Register;
