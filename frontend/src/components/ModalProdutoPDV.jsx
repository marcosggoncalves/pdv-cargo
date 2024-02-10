
import { Modal, Form, Col, Row } from 'react-bootstrap';
import React, { useState, useEffect } from 'react';
import { toast } from 'react-toastify';
import Swal from 'sweetalert2';
import request from '../request/index';

import ListagemImagemPDV from './ListagemImagemPDV';
import BtnCarregamentoPDV from './BtnCarregamentoPDV';
import HistoricoProdutoPDV from './HistoricoProdutoPDV';

function ModalProdutoPDV(props) {
  const { produto } = props;

  const [errors, setErrors] = useState({});
  const [descricao, setDescricao] = useState('');
  const [valorVenda, setValorVenda] = useState('');
  const [estoque, setEstoque] = useState('');
  const [imagens, setImagens] = useState([]);
  const [loading, setLoading] = useState(false);

  const limpar = () => {
    setDescricao('');
    setEstoque(1);
    setImagens([]);
    setValorVenda(0);
    setErrors({});
    setLoading(false)

    props.onHide();
  }

  const getImagens = (e) => {
    setImagens(Array.from(e.target.files));
  };

  useEffect(() => {
    if (produto) {
      setDescricao(produto.descricao);
      setEstoque(produto.estoque);
      setValorVenda(produto.valor_venda);
      return;
    }
  }, [produto]);

  const salvar = async () => {
    setLoading(true);

    try {

      const url = produto && produto.id ? `${process.env.REACT_APP_API_URL}/produto/${produto.id}` : `${process.env.REACT_APP_API_URL}/produto`

      const response = await request.post(url, {
        valor_venda: valorVenda,
        descricao,
        estoque,
        imagens
      }, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      const { status, message } = response.data;

      if (status) {
        toast.success(message, { position: 'top-right' });

        produto && produto.id ? props.carregamentoProduto(produto.id) : props.carregamentoProdutos();

        limpar();
      };

    } catch (error) {

      const { data } = error.response;

      if (data && data.errors) {
        setLoading(false);

        setErrors(data.errors);

        toast.warning(data.message, { position: 'top-right' });

        return;
      };

      toast.error(data.message, { position: 'top-right' });
    }
  };

  const excluirImagem = (id) => {
    Swal.fire({
      title: ` PDV.Cargo!`,
      text: `Deseja excluir imagem do produto?`,
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
          const response = await request.delete(`${process.env.REACT_APP_API_URL}/produto-excluir-imagem/${id}`);

          const { status, message } = response.data;

          if (status) {
            toast.success(message, { position: 'top-right' });

            props.carregamentoProduto(produto.id);
          };

        } catch (error) {
          const { data } = error.response;

          toast.error(data.message, { position: 'top-right' });
        }
      }
    });
  }

  return (
    <Modal
      {...props}
      size="lg"
      aria-labelledby="contained-modal-title-vcenter"
      centered
    >
      <Modal.Header closeButton>
        <Modal.Title id="contained-modal-title-vcenter">
          {produto && produto.id ? `Ficha Cadastral - Cod#${produto.id}` : 'Novo Produto'}
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Row>
          <Col>
            <Form>
              <Form.Group className="mb-3" controlId="loginPDVCargo">
                <Form.Label>Valor:</Form.Label>
                <Form.Control type="number"
                  placeholder="Informe o valor do produto:"
                  value={valorVenda}
                  onChange={(e) => setValorVenda(e.target.value)}
                />
                <Form.Text className="text-muted">
                  {errors.valor_venda && errors.valor_venda[0]}
                </Form.Text>
              </Form.Group>

              <Form.Group className="mb-3" controlId="cadastroProduto">
                <Form.Label>Estoque:</Form.Label>
                <Form.Control type="number"
                  placeholder="Informe uma quantidade para o produto:"
                  value={estoque}
                  onChange={(e) => setEstoque(e.target.value)}
                />
                <Form.Text className="text-muted danger">
                  {errors.estoque && errors.estoque[0]}
                </Form.Text>
              </Form.Group>
              <Form.Group as={Col} className="mb-3">
                <Form.Label>Descrição:</Form.Label>
                <Form.Control as="textarea" rows={3}
                  value={descricao}
                  onChange={(e) => setDescricao(e.target.value)}
                />
                <Form.Text className="text-muted danger">
                  {errors.descricao && errors.descricao[0]}
                </Form.Text>
              </Form.Group>

              <Form.Group controlId="imagens" className="mb-3">
                <Form.Label>Adicionar novas imagens:</Form.Label>
                <Form.Control
                  type="file"
                  multiple
                  onChange={getImagens}
                />
              </Form.Group>
            </Form>
          </Col>
          <Col>
            {produto && produto.imagens ?
              <ListagemImagemPDV
                imagens={produto.imagens}
                eventoClicar={excluirImagem}
              /> : <></>
            }
            {produto && produto.historico ?
              <HistoricoProdutoPDV
                historico={produto.historico}
              /> : <></>
            }
          </Col>
        </Row>
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

export default ModalProdutoPDV;
