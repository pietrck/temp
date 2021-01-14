CREATE TABLE `backup_log` (
  `id` int(11) NOT NULL,
  `id_title` int(11) NOT NULL,
  `log` blob DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `backup_report` (
  `id` int(11) NOT NULL,
  `id_backupLog` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `backup_title` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_hardware` int(11) DEFAULT NULL,
  `id_tools` int(11) DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `config_geral` (
  `id` int(11) NOT NULL,
  `nome_config` varchar(255) NOT NULL,
  `valor_config` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `costumers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `date_register` datetime NOT NULL,
  `date_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `hardwares` (
  `id` int(11) NOT NULL,
  `id_costumer` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_register` datetime NOT NULL,
  `date_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tools` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `backup_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_title` (`id_title`);

--
-- Índices para tabela `backup_report`
--
ALTER TABLE `backup_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hardware` (`id_backupLog`),
  ADD KEY `id_status` (`id_status`);

--
-- Índices para tabela `backup_title`
--
ALTER TABLE `backup_title`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tools` (`id_tools`),
  ADD KEY `id_hardware` (`id_hardware`);

--
-- Índices para tabela `config_geral`
--
ALTER TABLE `config_geral`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `costumers`
--
ALTER TABLE `costumers`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `hardwares`
--
ALTER TABLE `hardwares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_costumer` (`id_costumer`,`id_type`),
  ADD KEY `id_type` (`id_type`);

--
-- Índices para tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `backup_log`
--
ALTER TABLE `backup_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `backup_report`
--
ALTER TABLE `backup_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `backup_title`
--
ALTER TABLE `backup_title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `config_geral`
--
ALTER TABLE `config_geral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `costumers`
--
ALTER TABLE `costumers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `hardwares`
--
ALTER TABLE `hardwares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `tools`
--
ALTER TABLE `tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `backup_log`
--
ALTER TABLE `backup_log`
  ADD CONSTRAINT `backup_log_ibfk_1` FOREIGN KEY (`id_title`) REFERENCES `backup_title` (`id`);

--
-- Limitadores para a tabela `backup_report`
--
ALTER TABLE `backup_report`
  ADD CONSTRAINT `backup_report_ibfk_2` FOREIGN KEY (`id_status`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `backup_report_ibfk_3` FOREIGN KEY (`id_backupLog`) REFERENCES `backup_log` (`id`);

--
-- Limitadores para a tabela `backup_title`
--
ALTER TABLE `backup_title`
  ADD CONSTRAINT `backup_title_ibfk_1` FOREIGN KEY (`id_tools`) REFERENCES `tools` (`id`),
  ADD CONSTRAINT `backup_title_ibfk_2` FOREIGN KEY (`id_hardware`) REFERENCES `hardwares` (`id`);

--
-- Limitadores para a tabela `hardwares`
--
ALTER TABLE `hardwares`
  ADD CONSTRAINT `hardwares_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id`),
  ADD CONSTRAINT `hardwares_ibfk_2` FOREIGN KEY (`id_costumer`) REFERENCES `costumers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
