import React, { useEffect, useState } from 'react';
import { toast } from 'react-toastify';
import request from '../../request/index';
import Swal from 'sweetalert2';

import TabelaPDV from '../../components/TabelaPDV';
import CarregamentoPDV from '../../components/CarregamentoPDV';
import TituloContainerPDV from '../../components/TituloContainerPDV';
import ModalPedidoPDV from '../../components/ModalPedidoPDV';
import ModalPedidoDetalharPDV from '../../components/ModalPedidoDetalharPDV';

const Pedidos = () => {
  const [pedidoID, setPedidoID] = useState(null);
  const [pedidos, setPedidos] = useState([]);
  const [currentPagina, setCurrentPagina] = useState();
  const [totalPagina, setTotalPagina] = useState();
  const [loading, setLoading] = useState(false);
  const [modalCadastro, setModalCadastro] = useState(false);
  const [modalDetalhar, setModalDetalhar] = useState(false);

  const headers = [
    { coluna: 'id', titulo: 'ID', className: 'text-center' },
    { coluna: 'data_pedido', titulo: 'Data Pedido', className: 'text-center', badge: true },
    { coluna: 'total', titulo: 'Valor Total', className: 'text-center' },
    { coluna: 'situacao', titulo: 'Status', className: 'text-center' },
    { coluna: 'acoes', titulo: 'Ações', className: 'text-center', icon: 'icon' }
  ];

  const getPedidos = async (currentPagina = 1) => {
    setLoading(true);
    
    try {
      const response = await request.get(`${process.env.REACT_APP_API_URL}/pedidos`, {
        params: {
          page: currentPagina
        }
      });

      const { pedidos } = response.data;

      setPedidos(pedidos.data);

      setTotalPagina(pedidos.last_page);

      setCurrentPagina(pedidos.current_page);

      setLoading(false);

    } catch (error) {

      const { data } = error.response;

      toast.error(data.message, { position: 'top-right' });
    }
  }

  useEffect(() => {
    getPedidos();
  }, []);

  const modalCadastroReset = (open = false) => {
    setModalCadastro(open);
  }

  const modalDetalharReset = (open = false, id) => {
    setModalDetalhar(open);
    setPedidoID(id);
  }

  const excluirPedido = (id) => {
    Swal.fire({
      title: ` PDV.Cargo!`,
      text: `Deseja excluir o pedido?`,
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
          const response = await request.delete(`${process.env.REACT_APP_API_URL}/pedido/${id}`);

          const { status, message } = response.data;

          if (status) {
            toast.success(message, { position: 'top-right' });

            getPedidos();
          };

        } catch (error) {
          const { data } = error.response;

          toast.error(data.message, { position: 'top-right' });
        }
      }
    });
  }

  return (
    <div className="container mt-3">
      <ModalPedidoPDV show={modalCadastro}
        onHide={modalCadastroReset}
        carregamento={getPedidos}
      />
      <ModalPedidoDetalharPDV 
        show={modalDetalhar}
        onHide={modalDetalharReset}
        carregamento={getPedidos}
        id={pedidoID}
      />
      {
        loading ?
          <CarregamentoPDV /> :
          <div>
            <TituloContainerPDV
              titulo='Pedidos'
              clickEventoBtn={() => modalCadastroReset(true)}
              Titulobtn="+ Novo Pedido"
            />
            <TabelaPDV
              dados={pedidos}
              currentPagina={currentPagina}
              totalPagina={totalPagina}
              mudarPagina={getPedidos}
              headers={headers}
              eventoClickEditar={(item) => modalDetalharReset (true, item.id)}
              eventoClickDelete={(item) => excluirPedido(item.id)}
            />
          </div>
      }
    </div>
  );
};

export default Pedidos;
