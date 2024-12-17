<?php
require_once realpath(dirname(__DIR__) . '/config/database.php');

// Buscar banners adicionados pelo administrador
$sqlBanners = "SELECT ImagemBanner, Titulo FROM Banners ORDER BY DataCriacao DESC";
$stmtBanners = $pdo->query($sqlBanners);
$banners = $stmtBanners->fetchAll(PDO::FETCH_ASSOC);

// Buscar eventos com palestrante e imagens
$sqlEventos = "SELECT e.EventoId, e.NomeEvento, e.DataInicioEvento, e.DataFimEvento, 
                      e.HorarioInicio, e.HorarioTermino, e.LocalEvento, e.TipoEvento, 
                      e.ImagemEvento, e.Palestrante, d.NomeDepartamento, e.VagasDisponiveis
               FROM Eventos e
               JOIN Departamentos d ON e.DepartamentoEventoId = d.DepartamentoId
               WHERE e.VagasDisponiveis > 0";
$stmtEventos = $pdo->query($sqlEventos);
$eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);

// Combina banners e eventos no mesmo array para o carrossel
$itensCarrossel = array_merge(
    array_map(fn($banner) => ['type' => 'banner', 'data' => $banner], $banners),
    array_map(fn($evento) => ['type' => 'evento', 'data' => $evento], $eventos)
);

// Ordena os itens do carrossel (se necessário)
usort($itensCarrossel, function ($a, $b) {
    return $a['type'] === 'banner' ? -1 : 1; // Coloca banners antes, se necessário
});

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}
?>

<!-- Carrossel de Banners e Eventos -->
<div id="carrosselUnico" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($itensCarrossel as $index => $item): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <?php if ($item['type'] === 'banner'): ?>
                    <!-- Slide do Banner -->
                    <img src="<?php echo htmlspecialchars($item['data']['ImagemBanner']); ?>" 
                         class="d-block w-100" alt="Banner" style="height: 400px; object-fit: cover;">
                    <?php if (!empty($item['data']['Titulo'])): ?>
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                            <h5><?php echo htmlspecialchars($item['data']['Titulo']); ?></h5>
                        </div>
                    <?php endif; ?>
                <?php elseif ($item['type'] === 'evento'): ?>
                    <!-- Slide do Evento -->
                    <img src="<?php echo htmlspecialchars($item['data']['ImagemEvento'] ?: '/Eventosfaculdade/public/images/default-evento.jpg'); ?>" 
                         class="d-block w-100" alt="Imagem do Evento" style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                        <h5><?php echo htmlspecialchars($item['data']['NomeEvento']); ?></h5>
                        <p><strong>Departamento:</strong> <?php echo htmlspecialchars($item['data']['NomeDepartamento']); ?></p>
                        <p><strong>Data:</strong> <?php echo formatarData($item['data']['DataInicioEvento']); ?> a <?php echo formatarData($item['data']['DataFimEvento']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carrosselUnico" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carrosselUnico" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Próximo</span>
    </button>
</div>

<!-- Lista de Eventos Disponíveis -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Eventos Disponíveis</h2>
    <div class="row">
        <?php if (!empty($eventos)): ?>
            <?php foreach ($eventos as $index => $evento): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo htmlspecialchars($evento['ImagemEvento'] ?: '/Eventosfaculdade/public/images/default-evento.jpg'); ?>" 
                             class="card-img-top" alt="Imagem do Evento" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                            <p><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                            <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                            <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                            <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                            <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                            <p><strong>Vagas Disponíveis:</strong> <?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></p>
                            <!-- Botão Inscrever-se -->
                            <form method="POST" action="/Eventosfaculdade/src/controllers/InscreverEventoController.php">
                                <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                <button class="btn custom-ocean w-100 mb-2">Inscrever-se</button>
                            </form>
                            <!-- Botão Ver Mais -->
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalEvento<?php echo $index; ?>">Ver Descrição</button>
                        </div>
                    </div>
                </div>

                <!-- Modal para o Evento -->
                <div class="modal fade" id="modalEvento<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalEventoLabel<?php echo $index; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEventoLabel<?php echo $index; ?>"><?php echo htmlspecialchars($evento['NomeEvento']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($evento['DescricaoEvento'] ?? 'Nenhuma descrição disponível.'); ?></p>
                                <p><strong>Departamento:</strong> <?php echo htmlspecialchars($evento['NomeDepartamento']); ?></p>
                                <p><strong>Data:</strong> <?php echo formatarData($evento['DataInicioEvento']); ?> a <?php echo formatarData($evento['DataFimEvento']); ?></p>
                                <p><strong>Horário:</strong> <?php echo htmlspecialchars($evento['HorarioInicio']); ?> - <?php echo htmlspecialchars($evento['HorarioTermino']); ?></p>
                                <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['LocalEvento']); ?></p>
                                <p><strong>Palestrante:</strong> <?php echo htmlspecialchars($evento['Palestrante'] ?? 'Não informado'); ?></p>
                                <p><strong>Vagas Disponíveis:</strong> <?php echo htmlspecialchars($evento['VagasDisponiveis']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <form method="POST" action="/Eventosfaculdade/src/controllers/InscreverEventoController.php">
                                    <input type="hidden" name="evento_id" value="<?php echo $evento['EventoId']; ?>">
                                    <button class="btn btn-primary">Inscrever-se</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fim do Modal -->
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="alert alert-warning text-center">Nenhum evento disponível no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
