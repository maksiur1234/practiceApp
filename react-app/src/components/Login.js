import React, { useState, useContext } from 'react';
import { AuthContext } from '../auth-context';
import { useNavigate, Link } from 'react-router-dom';
import api from '../api';
import '../css/Login.css';

const Login = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });
    const [errors, setErrors] = useState({});
    const authContext = useContext(AuthContext);

    const handleInputChange = (event) => {
        const { name, value } = event.target;
        setFormData((prevData) => ({
            ...prevData,
            [name]: value,
        }));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        const { email, password } = formData;

        if (!email || !password) {
            const newErrors = {};
            if (!email) {
                newErrors.email = 'Email is required';
            }
            if (!password) {
                newErrors.password = 'Password is required';
            }
            setErrors(newErrors);
            return;
        }

        try {
            // execute response from api
            const response = await api.post('/login', { email, password });

            // set loign user in authcontext
            authContext.setUser(response.data.user);

            // save user in localStorage after loign
            localStorage.setItem('user', JSON.stringify(response.data.user));

            navigate('/dashboard');
        } catch (error) {
            console.error(error.response.data);
            const newErrors = {
                email: 'Invalid credentials',
                password: 'Invalid credentials',
            };
            setErrors(newErrors);
        }
    };

    return (
        <div className="login-form">
            <h2>Login</h2>
            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label htmlFor="email" className="form-label">Email Address</label>
                    <input
                        type="email"
                        className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                        id="email"
                        name="email"
                        value={formData.email}
                        onChange={handleInputChange}
                    />
                    {errors.email && <div className="invalid-feedback">{errors.email}</div>}
                </div>
                <div className="mb-3">
                    <label htmlFor="password" className="form-label">Password</label>
                    <input
                        type="password"
                        className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                        id="password"
                        name="password"
                        value={formData.password}
                        onChange={handleInputChange}
                    />
                    {errors.password && <div className="invalid-feedback">{errors.password}</div>}
                </div>
                <div className="row mb-0">
                    <div className="col-md-8 offset-md-4">
                        <button type="submit" className="btn btn-primary">Login</button>
                    </div>
                </div>
            </form>
            <div className="register-link">
                Don't have an account? <Link to="/register">Register here</Link>
            </div>
        </div>
    );
};

export default Login;
