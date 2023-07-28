import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api from '../api'; // Importujemy moduł api do wykonywania żądań HTTP
import '../css/Login.css'; // Importujemy nasz plik stylu CSS

const Login = () => {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });
    const [errors, setErrors] = useState({});

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

        // Walidacja pól formularza
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
            // Wykonaj żądanie logowania do API
            const response = await api.post('/login', { email, password });
            // Jeśli logowanie powiodło się, przekieruj użytkownika na stronę dashboard
            navigate('/dashboard');
        } catch (error) {
            // Obsługa błędów, np. wyświetlenie komunikatu o nieudanym logowaniu
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
                <div className="mb-3">
                    <div className="form-check">
                        <input
                            className="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember"
                        />
                        <label className="form-check-label" htmlFor="remember">
                            Remember Me
                        </label>
                    </div>
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
