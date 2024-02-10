import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import { Container, Navbar, Nav } from 'react-bootstrap';
import Home from './views/Home/Index';
import Login from './views/Login/Index';
import Pedidos from './views/Pedidos/Index';
import Produtos from './views/Produtos/Index';
import Usuarios from './views/Usuarios/Index';
import RotaAutenticadaPDV from './components/RotaAutenticadaPDV';

function App() {
  const liberarMenu = () => {
    if (window.localStorage.getItem('token.pdv.cargo')) {
      return true;
    }
    return false;
  }

  return (
    <Router>
      <Navbar bg="dark" variant="dark">
        <Container>
          <Navbar.Brand href="/">PDV.Cargo</Navbar.Brand>
          {liberarMenu()
            &&
            <Nav className="me-auto">
              <Nav.Link href="/">Home</Nav.Link>
              <Nav.Link href="/pedidos">Pedidos</Nav.Link>
              <Nav.Link href="/produtos">Produtos</Nav.Link>
              <Nav.Link href="/usuarios">Usuários</Nav.Link>
            </Nav>
          }
        </Container>
      </Navbar>
      <Container className="mt-3">
        <Switch>
          <RotaAutenticadaPDV exact path="/" component={Home} />
          <RotaAutenticadaPDV exact path="/pedidos" component={Pedidos} />
          <RotaAutenticadaPDV exact path="/produtos" component={Produtos} />
          <RotaAutenticadaPDV exact path="/usuarios" component={Usuarios} />
          <Route exact path="/login" component={Login} />
        </Switch>
      </Container>
    </Router>
  );
}

export default App;
