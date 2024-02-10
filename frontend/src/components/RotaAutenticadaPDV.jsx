import React from 'react';
import { Route, useHistory, Redirect } from 'react-router-dom';
import { toast } from 'react-toastify';
import request from '../request/index';

const Autenticado = ({ component: Component }) => {

    const history = useHistory();

    const verificarToken = async () => {
        let token = window.localStorage.getItem('token.pdv.cargo');

        try {
            await request.post(`${process.env.REACT_APP_API_URL}/login-check`, {}, {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

        } catch (error) {
            window.localStorage.clear();

            toast.error('Acesso não autorizado! Tente fazer o login novamente!', { position: 'top-right' });

            history.push('/login');
        }
    }

    return (
        <Route
            render={(props) =>
                verificarToken() ? <Component {...props} /> : <Redirect to="/login" />
            }
        />
    );
};

export default Autenticado;
