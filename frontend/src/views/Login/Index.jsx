import React, { useState } from 'react';
import { Form } from 'react-bootstrap';
import { toast } from 'react-toastify';
import { useHistory } from 'react-router-dom';
import request from '../../request/index';

import BtnCarregamentoPDV from '../../components/BtnCarregamentoPDV';

function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const history = useHistory();

  const carregar = (load = false, errors = {}) => {
    setLoading(load);
    setErrors(errors);
  }

  const entrar = async () => {
    setLoading(true);

    try {
      const response = await request.post(`${process.env.REACT_APP_API_URL}/login`, {
        email,
        password,
      });

      const { token, usuario } = response.data;

      window.localStorage.setItem('token.pdv.cargo', token);

      window.localStorage.setItem('usuario.pdv.cargo', JSON.stringify(usuario));

      history.push('/');

      history.go();

      carregar();

    } catch (error) {

      const { data } = error.response;

      if (data && data.errors) {
        toast.warning(data.message, { position: 'top-right' });

        return carregar(false, data.errors);;
      };

      toast.error(data.message, { position: 'top-right' });

      carregar();
    }
  };

  return (
    <Form className='login'>
      <Form.Group className="mb-3" controlId="loginPDVCargo">
        <Form.Label>Email:</Form.Label>
        <Form.Control type="email"
          placeholder="Informe seu email empresarial:"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
        <Form.Text className="text-muted">
          {errors.email && errors.email[0]}
        </Form.Text>
      </Form.Group>
      <Form.Group className="mb-3" controlId="formBasicPassword">
        <Form.Label>Senha:</Form.Label>
        <Form.Control type="password"
          placeholder="Informe sua senha:"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
        <Form.Text className="text-muted danger">
          {errors.password && errors.password[0]}
        </Form.Text>
      </Form.Group>
      <BtnCarregamentoPDV
        titulo="Entrar"
        clickEvento={entrar}
        loading={loading}
      />
    </Form>
  );
}

export default Login;