
import React from 'react';
import { Button } from 'react-bootstrap';

const TituloContainerPDV = ({ titulo, Titulobtn, clickEventoBtn }) => {
  return (
    <div className='mb-4'>
      <h3>{titulo}</h3>
      {Titulobtn != null ?
        <div className="mb-3 mt-3">
          <Button size="sm" variant="dark" onClick={clickEventoBtn}>{Titulobtn}</Button>
        </div> : <></>
      }
    </div>
  );
};

export default TituloContainerPDV;
