-- PARA CRIAÇÃO DO BANCO DE DADOS EM SQLITE3

CREATE TABLE IF NOT EXISTS users (

    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    username        TEXT NOT NULL,
    password_hash   TEXT,
    created_at      DATETIME CURRENT_TIMESTAMP
    
);