import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

const CompanyList = () => {
    const [companies, setCompanies] = useState([]);

    useEffect(() => {
        fetchCompanies();
    }, []);

    const fetchCompanies = async () => {
        try {
            const response = await axios.get('/api/companies');
            setCompanies(response.data);
        } catch (error) {
            console.error('Error fetching companies:', error);
        }
    };

    return (
        <div>
            <h2>Company List</h2>
            <ul>
                {companies.map((company) => (
                    <li key={company.id}>
                        <Link to={`/companies/${company.id}`}>{company.name}</Link>
                    </li>
                ))}
            </ul>
            <Link to="/create-company">Create New Company</Link>
        </div>
    );
};

export default CompanyList;
