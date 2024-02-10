import React from 'react';

import { Pagination } from 'react-bootstrap';

const PaginacaoPDV = ({ currentPagina, totalPagina, mudarPagina }) => {
    return (
        <div>
            <div className='mb-3'>
                {`(${totalPagina}) - Paginas encontrados!`}
            </div>
            <Pagination className="paginacaoPDV">
                <Pagination.Prev
                    disabled={currentPagina === 1}
                    onClick={() => mudarPagina(currentPagina - 1)}
                />
                {Array.from({ length: totalPagina }, (_, index) => (
                    <Pagination.Item

                        key={index + 1}
                        active={index + 1 === currentPagina}
                        onClick={() => mudarPagina(index + 1)}
                    >
                        {index + 1}
                    </Pagination.Item>
                ))}
                <Pagination.Next
                    disabled={currentPagina === currentPagina}
                    onClick={() => mudarPagina(currentPagina + 1)}
                />
            </Pagination>
        </div>
    );
};

export default PaginacaoPDV;
