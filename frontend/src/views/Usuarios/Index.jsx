import React, { useEffect, useState } from 'react';
import { toast } from 'react-toastify';

import Swal from 'sweetalert2';
import request from '../../request/index';

import TabelaPDV from '../../components/TabelaPDV';
import CarregamentoPDV from '../../components/CarregamentoPDV';
import TituloContainerPDV from '../../components/TituloContainerPDV';
import ModalUsuarioPDV from '../../components/ModalUsuarioPDV';

const Usuarios = () => {
  const [usuarios, setUsuarios] = useState([]);
  const [usuario, setUsuario] = useState('');
  const [currentPagina, setCurrentPagina] = useState();
  const [totalPagina, setTotalPagina] = useState();
  const [loading, setLoading] = useState(false);
  const [modalCadastro, setModalCadastro] = useState(false);

  const headers = [
    { coluna: 'id', titulo: 'ID', className: 'text-left' },
    { coluna: 'nome', titulo: 'Usuário Sistema', className: 'text-left' },
    { coluna: 'email', titulo: 'E-mail', className: 'text-left' },
    { coluna: 'acoes', titulo: 'Ações', className: 'text-center', icon: 'icon' }
  ];

  const getUsuarios = async (currentPagina = 1) => {
    setLoading(true);

    try {
      const response = await request.get(`${process.env.REACT_APP_API_URL}/usuarios`, {
        params: {
          page: currentPagina
        }
      });

      const { usuarios } = response.data;

      setUsuarios(usuarios.data);

      setTotalPagina(usuarios.last_page);

      setCurrentPagina(usuarios.current_page);

      setLoading(false);

    } catch (error) {

      const { data } = error.response;

      toast.error(data.message, { position: 'top-right' });
    }
  }

  const excluirUsuario = (id) => {
    Swal.fire({
      title: ` PDV.Cargo!`,
      text: `Deseja excluir o usuário? `,
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
          const response = await request.delete(`${process.env.REACT_APP_API_URL}/usuario/${id}`);

          const { status, message } = response.data;

          if (status) {
            toast.success(message, { position: 'top-right' });

            getUsuarios();
          };

        } catch (error) {
          const { data } = error.response;

          toast.error(data.message, { position: 'top-right' });
        }
      }
    });
  }

  const modalCadastroReset = (item = {},open = false) => {
    setUsuario(item);
    setModalCadastro(open);
  }


  useEffect(() => {
    getUsuarios();
  }, []);

  return (
    <div className="container mt-3">
      <ModalUsuarioPDV
        show={modalCadastro}
        usuario={usuario}
        onHide={modalCadastroReset}
        carregamentosUsuarios={getUsuarios}
      />
      {
        loading ?
          <CarregamentoPDV /> :
          <div>
            <TituloContainerPDV
              titulo='Usuários'
              clickEventoBtn={() => modalCadastroReset({}, true)}
              Titulobtn="+ Criar Novo Acesso"
            />
            <TabelaPDV
              dados={usuarios}
              currentPagina={currentPagina}
              totalPagina={totalPagina}
              mudarPagina={getUsuarios}
              headers={headers}
              eventoClickEditar={(item) => modalCadastroReset(item, true)}
              eventoClickDelete={(item) => excluirUsuario(item.id)}
            />
          </div>
      }
    </div>
  );
};

export default Usuarios;
