// src/index.js

import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter as Router } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';

import './index.css';
import App from './App';

import 'bootstrap/dist/css/bootstrap.min.css';  
import 'react-toastify/dist/ReactToastify.css';

ReactDOM.render(
  <React.StrictMode>
    <Router>
      <App />
      <ToastContainer position="top-right" autoClose={5000} />
    </Router>
  </React.StrictMode>,

  document.getElementById('root')
);

