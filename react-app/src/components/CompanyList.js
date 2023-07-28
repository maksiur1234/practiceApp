import React from 'react';

const CompanyList = ({ companies }) => {
    return (
        <div>
            {companies.map((company, index) => (
                <div key={index}>
                    <h3>Company Name: {company.name}</h3>
                    <p>Email: {company.email}</p>
                    {/* Wyświetl prośby o wizytę */}
                </div>
            ))}
        </div>
    );
};

export default CompanyList;
