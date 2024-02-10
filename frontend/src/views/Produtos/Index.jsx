import React, { useEffect, useState } from 'react';
import { toast } from 'react-toastify';

import Swal from 'sweetalert2';
import request from '../../request/index';

import TabelaPDV from '../../components/TabelaPDV';
import CarregamentoPDV from '../../components/CarregamentoPDV';
import TituloContainerPDV from '../../components/TituloContainerPDV';
import ModalProdutoPDV from '../../components/ModalProdutoPDV';

const Produtos = () => {
  const [produtos, setProdutos] = useState([]);
  const [produto, setProduto] = useState({});
  const [currentPagina, setCurrentPagina] = useState();
  const [totalPagina, setTotalPagina] = useState();
  const [loading, setLoading] = useState(false);
  const [modalCadastro, setModalCadastro] = useState(false);

  const headers = [
    { coluna: 'id', titulo: 'ID', className: 'text-center' },
    { coluna: 'descricao', titulo: 'Descrição', className: 'text-left' },
    { coluna: 'valor_venda', titulo: 'Valor R$', className: 'text-center', badge: true },
    { coluna: 'estoque', titulo: 'Estoque(QTD)', className: 'text-center' },
    { coluna: 'acoes', titulo: 'Ações', className: 'text-center', icon: 'icon' }
  ];

  const getProdutos = async (currentPagina = 1) => {
    setLoading(true);

    try {
      const response = await request.get(`${process.env.REACT_APP_API_URL}/produtos`, {
        params: { page: currentPagina }
      });

      const { produtos } = response.data;

      setProdutos(produtos.data);

      setTotalPagina(produtos.last_page);

      setCurrentPagina(produtos.current_page);

      setLoading(false);

    } catch (error) {

      const { data } = error.response;

      toast.error(data.message, { position: 'top-right' });
    }
  }

  const excluirProduto = (id) => {
    Swal.fire({
      title: ` PDV.Cargo!`,
      text: `Deseja excluir o produto? Todos pedidos, imagens e historico serão perdido.`,
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
          const response = await request.delete(`${process.env.REACT_APP_API_URL}/produto/${id}`);

          const { status, message } = response.data;

          if (status) {
            toast.success(message, { position: 'top-right' });

            getProdutos();
          };

        } catch (error) {
          const { data } = error.response;

          toast.error(data.message, { position: 'top-right' });
        }
      }
    });
  }

  const getProduto = async (id) => {
    try {
      const response = await request.get(`${process.env.REACT_APP_API_URL}/produto/${id}`);

      const { status, produto } = response.data;

      if (status) {
        setProduto(produto);

        setModalCadastro(true)
      };

    } catch (error) {
      const { data } = error.response;

      if (data && data.errors) {
        setLoading(false);

        return toast.warning(data.message, { position: 'top-right' });;
      };

    }
  };

  const modalCadastroReset = (open = false) => {
    setProduto({});
    setModalCadastro(open);
  }

  useEffect(() => {
    getProdutos();
  }, []);

  return (
    <div className="container mt-3">
      <ModalProdutoPDV show={modalCadastro}
        onHide={modalCadastroReset}
        carregamentoProdutos={getProdutos}
        carregamentoProduto={getProduto}
        produto={produto}
      />
      {
        loading ?
          <CarregamentoPDV /> :
          <div>
            <TituloContainerPDV
              titulo='Meus Produtos'
              clickEventoBtn={() => modalCadastroReset(true)}
              Titulobtn="+ Novo Produto"
            />
            <TabelaPDV
              dados={produtos}
              currentPagina={currentPagina}
              totalPagina={totalPagina}
              mudarPagina={getProdutos}
              headers={headers}
              eventoClickEditar={(item) => getProduto(item.id)}
              eventoClickDelete={(item) => excluirProduto(item.id)}
            />
          </div>
      }
    </div>
  );
};

export default Produtos;
