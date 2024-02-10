
import React, { useEffect, useState } from 'react';
import Swal from 'sweetalert2';
import { Card, Button } from 'react-bootstrap';
import { useHistory } from 'react-router-dom';

const Home = () => {
  const [usuario, setUsuario] = useState({});

  const history = useHistory();

  const getLogin = () => {
    const storage = window.localStorage.getItem('usuario.pdv.cargo');

    if (storage) {
      setUsuario(JSON.parse(storage));
    }
  }

  const encerrarSessao = () => {
    Swal.fire({
      title: ` PDV.Cargo!`,
      text: `Deseja finalizar sua sessão no sistema?`,
      icon: "error",
      showCancelButton: true,
      confirmButtonColor: "#000",
      cancelButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: `Sim, pode finalizar`,
      reverseButtons: true,
    }).then(async (result) => {
      if (result.isConfirmed) {
        history.go();
        window.localStorage.clear();
      }
    });
  }

  useEffect(() => {
    getLogin();
  }, []);

  return (
    <div>
      <h1 className='mb-4'>Bem-vindo ao PDV.Cargo</h1>
      <Card>
        <Card.Header>Olá senhor(a), {usuario.nome} | Email: {usuario.email}</Card.Header>
        <Card.Body>
          <Card.Title>Gerencie seus pedidos e produtos de qualquer lugar!</Card.Title>
          <Card.Text>
            Aqui você encontrará a melhor gestão para seus pedidos!
          </Card.Text>
          <Button variant="dark" onClick={encerrarSessao}>Encerrar sessão</Button>
        </Card.Body>
      </Card>
    </div>
  );
};

export default Home;
