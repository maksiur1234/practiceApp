import React from 'react';

const GiftList = ({ gifts }) => {
    return (
        <div>
            {gifts.map((gift, index) => (
                <div key={index}>
                    <h4>Title: {gift.title}</h4>
                    <p>Description: {gift.description}</p>
                    {/* Wyświetl szczegóły prezentu */}
                </div>
            ))}
        </div>
    );
};

export default GiftList;
