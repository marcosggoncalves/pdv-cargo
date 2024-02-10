
import { Modal, Form } from 'react-bootstrap';
import React, { useState, useEffect } from 'react';
import { toast } from 'react-toastify';
import request from '../request/index';

import BtnCarregamentoPDV from './BtnCarregamentoPDV';

function ModalUsuarioPDV(props) {
  const { usuario } = props;

  const [errors, setErrors] = useState({});
  const [nome, setNome] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const camposFormulario = (nome = '', email = '', password = '', errors = {}, loading = false, hide = true) => {
    setNome(nome);
    setEmail(email);
    setPassword(password);
    setErrors(errors);
    setLoading(loading)

    if (hide) props.onHide();
  }

  const salvar = async () => {

    setLoading(true);

    try {

      const url = usuario && usuario.id ? `${process.env.REACT_APP_API_URL}/usuario/${usuario.id}` : `${process.env.REACT_APP_API_URL}/usuario`

      const response = await request.post(url, { email, nome, password });

      const { status, message } = response.data;

      if (status) {
        toast.success(message, { position: 'top-right' });

        props.carregamentosUsuarios();

        camposFormulario();
      };

    } catch (error) {

      const { data } = error.response;

      if (data && data.errors) {
        camposFormulario(nome, email, password, data.errors, false, false);

        return toast.warning(data.message, { position: 'top-right' });
      };

      toast.error(data.message, { position: 'top-right' });
    }
  };

  
  useEffect(() => {
    if (usuario) {
      setNome(usuario.nome);
      setEmail(usuario.email);
      return;
    }
  }, [usuario]);

  return (
    <Modal
      {...props}
      size="md"
      aria-labelledby="contained-modal-title-vcenter"
      centered
    >
      <Modal.Header closeButton>
        <Modal.Title id="contained-modal-title-vcenter">
          {usuario && usuario.id ? `Editar Acesso` : 'Criar Novo Acesso'}
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Form.Group className="mb-3" controlId="loginPDVCargo">
          <Form.Label>Nome Completo:</Form.Label>
          <Form.Control
            type="nome"
            placeholder="Informe o nome completo do funcionário:"
            value={nome}
            onChange={(e) => setNome(e.target.value)}
          />
          <Form.Text className="text-muted">
            {errors.nome && errors.nome[0]}
          </Form.Text>
        </Form.Group>
        <Form.Group className="mb-3" controlId="loginPDVCargo">
          <Form.Label>Email:</Form.Label>
          <Form.Control
            type="email"
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
      </Modal.Body>
      <Modal.Footer>
        <BtnCarregamentoPDV
          titulo='Salvar'
          loading={loading}
          clickEvento={salvar}
        />
      </Modal.Footer>
    </Modal>
  );
}

export default ModalUsuarioPDV;
