
import { Modal, Form, Row, Col, Button, ListGroup } from 'react-bootstrap';
import React, { useState, useEffect } from 'react';
import { toast } from 'react-toastify';
import Swal from 'sweetalert2';
import request from '../request/index';
import BtnCarregamentoPDV from './BtnCarregamentoPDV';

function ModalPedidoDetalharPDV(props) {
  const [produtoID, setProdutoID] = useState(null);
  const [quantidade, setQuantidade] = useState(0);
  const [pedido, setPedido] = useState(0);
  const [listagem, setListagem] = useState([]);
  const [loading, setLoading] = useState(false);

  const resetarCampos = (load = false, recarregar = false) => {
    setProdutoID('');
    setQuantidade(1);
    setLoading(load);

    if (recarregar) detalharPedido();
  }

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

  const detalharPedido = async () => {
    try {
      const response = await request.get(`${process.env.REACT_APP_API_URL}/pedido/${props.id}`);

      const { pedido } = response.data;

      setPedido(pedido);

    } catch (error) {

      const { data } = error.response;

      toast.error(data.message, { position: 'top-right' });
    }
  }

  const adicionarProduto = async () => {
    if (!produtoID) return;

    setLoading(true);

    var produtos = [
      {
        produto_id: produtoID,
        quantidade
      }
    ];

    try {
      const url = `${process.env.REACT_APP_API_URL}/pedido-adicionar-produto/${pedido.id}`

      const response = await request.post(url, {
        produtos
      });

      const { status, message } = response.data;

      if (status) {
        toast.success(message, { position: 'top-right' });

        resetarCampos(false, true);
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

  const excluirItem = (id) => {
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
        try {
          const response = await request.delete(`${process.env.REACT_APP_API_URL}/pedido-item/${id}`);

          const { status, message } = response.data;

          if (status) toast.success(message, { position: 'top-right' });

          resetarCampos(false, true);

        } catch (error) {

          const { data } = error.response;

          toast.error(data.message, { position: 'top-right' });
        }
      }
    });
  };

  const execute = () => {
    getListagem();

    setPedido({});

    resetarCampos(false, true);
  }

  return (
    <Modal
      {...props}
      size="lg"
      aria-labelledby="contained-modal-title-vcenter"
      centered
      onShow={execute}
    >
      <Modal.Header closeButton>
        <Modal.Title id="contained-modal-title-vcenter">
          Detalhar pedido#{pedido.id}
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

              <div className='mt-2'>
                <BtnCarregamentoPDV
                  titulo='Adicionar'
                  loading={loading}
                  clickEvento={adicionarProduto}
                />
              </div>
            </Form>
          </Col>
          <Col>
            <div>
              <h6 >Lista de produtos:</h6 >
              <span>Clique em cima para remover item lista!</span>
              <ListGroup  className='mt-3'>
                {pedido.produtos?.map((produto, index) => (
                  <div>
                    <ListGroup.Item onClick={() => excluirItem(produto.pivot.id)}><b>{index + 1} - {produto.descricao}</b> - Quantidade: {produto.pivot.quantidade}</ListGroup.Item>
                  </div>
                ))}
              </ListGroup>
            </div>
          </Col>
        </Row>
      </Modal.Body>
      <Modal.Footer>
        <h4> TOTAL: R$ {pedido?.total}</h4>
      </Modal.Footer>
    </Modal>
  );
}

export default ModalPedidoDetalharPDV;
