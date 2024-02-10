

import React from 'react';
import { Button, Spinner } from 'react-bootstrap';

const BtnCarregamentoPDV = ({ titulo, clickEvento, loading }) => {
    return (
        <Button variant="dark" onClick={clickEvento} disabled={loading}>
            {loading ? (
                <>
                    <Spinner animation="border" size="sm" className="me-2" />
                    Aguarde
                </>
            ) : (
                titulo
            )}
        </Button>
    );
};

export default BtnCarregamentoPDV;
