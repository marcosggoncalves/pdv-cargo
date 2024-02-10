
import React from 'react';
import { Container, Row, Col, Alert } from 'react-bootstrap';

const ListagemImagemPDV = ({ imagens, eventoClicar }) => {
  return (
    <Container>
      <div>
        <h6 className='mb-3' >Fotos:</h6 >
      </div>
      {
        imagens.length > 0 ?
          <Row className='listagem-imagem-pdv mb-2'>
            {imagens.map((imagem, index) => (
              <Col className='mb-2' key={index} xs={6} md={6} lg={3} onClick={() => eventoClicar(imagem.id)}>
                <img
                  src={`${process.env.REACT_APP_API}${imagem.url}`}
                  alt={`ImagemProduto${index + 1}`}
                  className="img-thumbnail"
                />
              </Col>
            ))}
          </Row>
          : <Alert variant="warning">Nenhuma imagem vinculada a esse produto!</Alert>
      }
    </Container>
  );
};

export default ListagemImagemPDV;
