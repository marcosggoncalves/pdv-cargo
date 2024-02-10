import React from 'react';
import { Table, Button, Badge } from 'react-bootstrap';
import { BsFillTrashFill, BsPencilSquare } from "react-icons/bs";
import PaginacaoPDV from './PaginacaoPDV';

const TabelaPDV = ({ dados, currentPagina, totalPagina, mudarPagina, headers, eventoClickEditar, eventoClickDelete }) => {

  const icons = (tipo = 'icon', item) => {
    if (['icon'].includes(tipo)) {
      return <div>
        <Button onClick={() => eventoClickEditar(item)} className='espaco-tabela-pdv' size='sm' variant='dark'><BsPencilSquare /></Button>
        <Button onClick={() => eventoClickDelete(item)} className='espaco-tabela-pdv' size='sm' variant='danger'><BsFillTrashFill /></Button>
      </div>
    }

    return 'N.A'
  }

  const renderizarTD = (item, header) => {
    if (header.badge) {
      return <Badge bg="dark">{item[header.coluna]}</Badge>;
    }

    if (header.icon) {
      return icons(header.icon, item)
    }

    return item[header.coluna]
  }

  return (
    <div>
      <Table bordered hover responsive striped="columns" size="sm">
        <thead>
          <tr>
            {headers.map((header) => (
              <th key={header.coluna} className={header.className}>{header.titulo}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {dados.map((item) => (
            <tr key={item.id}>
              {headers.map((header) => (
                <td key={Math.floor(Math.random() * 1000)} className={header.className}>
                  {renderizarTD(item, header)}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </Table>

      <PaginacaoPDV
        currentPagina={currentPagina}
        totalPagina={totalPagina}
        mudarPagina={mudarPagina}
      />
    </div>
  );
};

export default TabelaPDV;
