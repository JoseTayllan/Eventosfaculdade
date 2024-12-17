-- Table structure for table `Banners`

DROP TABLE IF EXISTS `Banners`;
CREATE TABLE `Banners` (
  `BannerId` int NOT NULL AUTO_INCREMENT,
  `ImagemBanner` varchar(255) NOT NULL,
  `Titulo` varchar(255) DEFAULT NULL,
  `DataCriacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`BannerId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Certificados`

DROP TABLE IF EXISTS `Certificados`;
CREATE TABLE `Certificados` (
  `CertificadoId` int NOT NULL AUTO_INCREMENT,
  `InscricaoId` int DEFAULT NULL,
  `ArquivoCertificado` longblob NOT NULL,
  `DataEmissaoCertificado` datetime NOT NULL,
  PRIMARY KEY (`CertificadoId`),
  KEY `InscricaoId` (`InscricaoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Cursos`

DROP TABLE IF EXISTS `Cursos`;
CREATE TABLE `Cursos` (
  `CursoId` int NOT NULL AUTO_INCREMENT,
  `NomeCurso` varchar(100) NOT NULL,
  PRIMARY KEY (`CursoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `CursosDepartamentos`

DROP TABLE IF EXISTS `CursosDepartamentos`;
CREATE TABLE `CursosDepartamentos` (
  `CursoDepartamentoId` int NOT NULL AUTO_INCREMENT,
  `CursoId` int DEFAULT NULL,
  `DepartamentoId` int DEFAULT NULL,
  PRIMARY KEY (`CursoDepartamentoId`),
  KEY `CursoId` (`CursoId`),
  KEY `DepartamentoId` (`DepartamentoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `CursosParticipantes`

DROP TABLE IF EXISTS `CursosParticipantes`;
CREATE TABLE `CursosParticipantes` (
  `CursoParticipanteId` int NOT NULL AUTO_INCREMENT,
  `CursoId` int DEFAULT NULL,
  `ParticipanteId` int DEFAULT NULL,
  PRIMARY KEY (`CursoParticipanteId`),
  KEY `CursoId` (`CursoId`),
  KEY `ParticipanteId` (`ParticipanteId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Departamentos`

DROP TABLE IF EXISTS `Departamentos`;
CREATE TABLE `Departamentos` (
  `DepartamentoId` int NOT NULL AUTO_INCREMENT,
  `NomeDepartamento` varchar(50) NOT NULL,
  PRIMARY KEY (`DepartamentoId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Eventos`

DROP TABLE IF EXISTS `Eventos`;
CREATE TABLE `Eventos` (
  `EventoId` int NOT NULL AUTO_INCREMENT,
  `NomeEvento` varchar(100) NOT NULL,
  `DataInicioEvento` datetime NOT NULL,
  `DataFimEvento` datetime NOT NULL,
  `HorarioInicio` time NOT NULL,
  `HorarioTermino` time NOT NULL,
  `LocalEvento` varchar(255) NOT NULL,
  `CargaHoraria` int NOT NULL,
  `DescricaoEvento` text,
  `ImagemEvento` varchar(255) DEFAULT NULL,
  `VagasDisponiveis` int NOT NULL DEFAULT '0',
  `TipoEvento` enum('Interno','Externo') NOT NULL,
  `DepartamentoEventoId` int DEFAULT NULL,
  `Palestrante` varchar(255) DEFAULT NULL,
  `ResponsavelEventoId` int DEFAULT NULL,
  `InstituicaoParceira` varchar(255) DEFAULT NULL,
  `PalestranteId` int DEFAULT NULL,
  PRIMARY KEY (`EventoId`),
  KEY `DepartamentoEventoId` (`DepartamentoEventoId`),
  KEY `ResponsavelEventoId` (`ResponsavelEventoId`),
  KEY `FK_Palestrante` (`PalestranteId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Inscricoes`

DROP TABLE IF EXISTS `Inscricoes`;
CREATE TABLE `Inscricoes` (
  `InscricaoId` int NOT NULL AUTO_INCREMENT,
  `EventoId` int DEFAULT NULL,
  `ParticipanteId` int DEFAULT NULL,
  `DataInscricao` datetime DEFAULT CURRENT_TIMESTAMP,
  `Compareceu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`InscricaoId`),
  KEY `EventoId` (`EventoId`),
  KEY `ParticipanteId` (`ParticipanteId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Participantes`

DROP TABLE IF EXISTS `Participantes`;
CREATE TABLE `Participantes` (
  `ParticipanteId` int NOT NULL AUTO_INCREMENT,
  `NomeParticipante` varchar(100) NOT NULL,
  `EmailParticipante` varchar(100) NOT NULL,
  `TipoParticipante` enum('Interno','Externo','Admin') NOT NULL,
  `NumeroMatricula` varchar(20) DEFAULT NULL,
  `CPF` varchar(11) DEFAULT NULL,
  `SenhaParticipante` varchar(255) NOT NULL,
  `DepartamentoParticipanteId` int DEFAULT NULL,
  PRIMARY KEY (`ParticipanteId`),
  UNIQUE KEY `EmailParticipante` (`EmailParticipante`,`CPF`),
  KEY `DepartamentoParticipanteId` (`DepartamentoParticipanteId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE Participantes ADD COLUMN TelefoneParticipante VARCHAR(15) NULL;

CREATE TABLE Configuracoes (
    ConfiguracaoId INT AUTO_INCREMENT PRIMARY KEY,
    Chave VARCHAR(50) UNIQUE NOT NULL,
    Valor TEXT NOT NULL
);
INSERT INTO Configuracoes (Chave, Valor)
VALUES ('senha_especial', '$2y$10$hVKXm3A2JivliEbA7XTrDOad5NKmZ2XwpUl.c98A8Gy5/9AFjTvvC');
