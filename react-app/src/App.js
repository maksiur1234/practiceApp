import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './auth-context';
import Home from './components/Home';
import Login from './components/Login';
import Register from './components/Register';
import Dashboard from './components/Dashboard';
import CompanyList from './components/CompanyList';
import CreateCompany from './components/CreateCompany';

const App = () => {
    return (
        <Router>
            <AuthProvider>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route path="/dashboard" element={<Dashboard />} />
                <Route exact path="/companies" element={<CompanyList />} />
                <Route exact path="/create-company" element={<CreateCompany />} />
            </Routes>
            </AuthProvider>
        </Router>
    );
};

export default App;
