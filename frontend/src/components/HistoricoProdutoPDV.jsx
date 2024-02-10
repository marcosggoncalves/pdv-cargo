import React from 'react';
import { Container, Row, ListGroup } from 'react-bootstrap';
import 'moment/locale/pt-br'; 

import moment from 'moment';
moment.locale('pt-br');


const HistoricoProdutoPDV = ({ historico }) => {

    const dataFormata = (data) => {
        return moment(data).format('DD MMMM YYYY  h:mm:ss a');
    }

    return (
        <Container>
            <div>
                <h6 className='mb-3'>Movimentações:</h6 >
            </div>
            <Row>
                <ListGroup className='listagem-historico-pdv '>
                    {historico.map((item) => (
                        <ListGroup.Item><b>{item.tipo}</b> realizada, sob quantidade: <b>({item.quantidade})</b>, dia {dataFormata(item.created_at)}.</ListGroup.Item>
                    ))}
                </ListGroup>
            </Row>
        </Container>
    );
};

export default HistoricoProdutoPDV;
