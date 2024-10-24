CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    senha VARCHAR(32) NOT NULL, -- Armazenamento do hash MD5
    nome_completo VARCHAR(100) NOT NULL,
    cep VARCHAR(8),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    endereco VARCHAR(150),
    numero VARCHAR(10),
    complemento VARCHAR(50),
    telefone VARCHAR(15),
    role ENUM('administrador', 'nutricionista', 'secretaria') NOT NULL
);

CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    idade INT NOT NULL,
    cep VARCHAR(10) NOT NULL,
    cidade VARCHAR(50),
    estado VARCHAR(2),
    endereco VARCHAR(100),
    numero VARCHAR(10),
    complemento VARCHAR(50)
);

CREATE TABLE evolucoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT,
    data_evolucao DATETIME,
    observacao TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);

CREATE TABLE dietas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT,
    data_dieta DATETIME,
    alimentos TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);