-- PARA CRIAÇÃO DO BANCO DE DADOS EM SQLITE3

CREATE TABLE IF NOT EXISTS posts (

    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    slug            TEXT NOT NULL UNIQUE,
    title           TEXT NOT NULL,
    date            TEXT NOT NULL,
    date_iso        DATE,
    category        TEXT,
    excerpt          TEXT,
    content         TEXT,
    reading_time    INTEGER,
    status          TEXT DEFAULT 'draft',
    created_at      TEXT DEFAULT CURRENT_TIMESTAMP
    
);