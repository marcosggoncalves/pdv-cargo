
import { Modal, Form, Row, Col, Button, ListGroup } from 'react-bootstrap';
import React, { useState, useEffect } from 'react';
import { BsPlusCircleFill } from "react-icons/bs";
import { toast } from 'react-toastify';
import Swal from 'sweetalert2';
import request from '../request/index';
import BtnCarregamentoPDV from './BtnCarregamentoPDV';

function ModalPedidoPDV(props) {
  const [produtoID, setProdutoID] = useState(null);
  const [quantidade, setQuantidade] = useState(0);
  const [produtos, setProdutos] = useState([]);
  const [listagem, setListagem] = useState([]);
  const [loading, setLoading] = useState(false);

  const getListagem = async () => {
    try {
      const response = await request.get(`${process.env.REACT_APP_API_URL}/produtos-listagem`);

      if (response && response.data.length == 0) toast.error('Nenhum produto cadastrado!', { position: 'top-right' });

      setListagem(response.data);

    } catch (error) {

      const { data } = error.response;

      toast.error(data.message, { position: 'top-right' });
    }
  }

  const realizarPedido = async () => {

    setLoading(true);

    try {

      const url = `${process.env.REACT_APP_API_URL}/relizar-pedido`

      const response = await request.post(url, { produtos });

      const { status, message } = response.data;

      if (status) {
        toast.success(message, { position: 'top-right' });

        props.carregamento();

        setLoading(false);

        props.onHide();
      };

    } catch (error) {

      const { data } = error.response;

      if (data && data.errors) {
        setLoading(false);

        return toast.warning(data.message, { position: 'top-right' });
      };

      setLoading(false);

      toast.error(data.message, { position: 'top-right' });
    }
  };

  const adicionarProduto = () => {
    setProdutos((prev) => [...prev, { produto_id: produtoID, quantidade: quantidade }]);

    setProdutoID('');

    setQuantidade(0);
  };

  const removerProduto = (item) => {
    Swal.fire({
      title: ` PDV.Cargo!`,
      text: `Deseja excluir item da sua lista?`,
      icon: "error",
      showCancelButton: true,
      confirmButtonColor: "#000",
      cancelButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: `Sim, pode excluir`,
      reverseButtons: true,
    }).then(async (result) => {
      if (result.isConfirmed) {
        setProdutos(produtos.filter((produto) => produto.produto_id !== item.produto_id));
      }
    });
  };

  const getProdutoDescricao = (id) => {
    return listagem.find((p) => p.id == id)?.descricao || 'N.A';
  }

  return (
    <Modal
      {...props}
      size="lg"
      aria-labelledby="contained-modal-title-vcenter"
      centered
      onShow={getListagem}
    >
      <Modal.Header closeButton>
        <Modal.Title id="contained-modal-title-vcenter">
          Novo Pedido
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Row>
          <Col>
            <Form>
              <Row>
                <Form.Group as={Col}>
                  <Form.Label>Produtos:</Form.Label>
                  <Form.Select
                    value={produtoID}
                    onChange={(e) => setProdutoID(e.target.value)}
                  >
                    <option >Selecione um produto</option>
                    {listagem.map((produto) => (
                      <option key={produto.id} value={produto.id}>{produto.descricao}</option>
                    ))}
                  </Form.Select>
                </Form.Group>
                <Form.Group as={Col}>
                  <Form.Label>Quantidade:</Form.Label>
                  <Form.Control type="number"
                    placeholder="Quantidade"
                    value={quantidade}
                    onChange={(e) => setQuantidade(e.target.value)}
                    disabled={!produtoID}
                  />
                </Form.Group>
              </Row>
              <Button disabled={!produtoID} size='sm' variant='dark' className='mt-3' onClick={adicionarProduto}><BsPlusCircleFill /> Adicionar</Button>
            </Form>
          </Col>
          <Col>
            <div>
              <h6 className='mb-4' >Lista de produtos:</h6 >
              <ListGroup>
                {produtos.map((produto, index) => (
                  <ListGroup.Item onClick={() => removerProduto(produto)}><b>{index + 1} - {getProdutoDescricao(produto.produto_id)}</b> - Quantidade: {produto.quantidade}</ListGroup.Item>
                ))}
              </ListGroup>
            </div>
          </Col>
        </Row>
      </Modal.Body>
      <Modal.Footer>
        <BtnCarregamentoPDV
          titulo='Salvar'
          loading={loading}
          clickEvento={realizarPedido}
        />
      </Modal.Footer>
    </Modal>
  );
}

export default ModalPedidoPDV;
