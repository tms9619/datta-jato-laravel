<!DOCTYPE html>
<html lang="pt-br">
	<head>

		<!-- Meta tags obrigatórias -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">

		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css')}}">

		<!-- DataTables -->
		<link rel="stylesheet" type="text/css" href="{{ asset('datatables/datatables.css')}}">

		<title>DattaJato</title>

	</head>
	<body>

		<!-- Início header -->
		<header>
			
			<nav class="navbar navbar-expand-md navbar-dark sticky-top bg-primary">
				
				<div class="container">
					
					<!-- Logo -->
					<a class="navbar-brand" href="{{ route('user_operador.index')}}">
						DattaJato
					</a>

					<!-- Menu Hamburguer -->
					<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav-target" type="button">
						<i class="fas fa-bars text-white"></i>
					</button>

					<!-- Navegação -->
					<div id="nav-target" class="collapse navbar-collapse">
						<ul class="navbar-nav ms-auto">
							<li class="nav-item dropdown">
								<a id="navbarDropdown" class="nav-link" data-bs-toggle="dropdown" href="#">
									<i class="fas fa-calendar-alt"></i> Agendamentos
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="{{ route('agendamento_andamento')}}">Em andamento</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="">Finalizado</a>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ route('logout')}}">
									<i class="fas fa-sign-out-alt"></i> Sair
								</a>
							</li>
						</ul>
					</div>

				</div>

			</nav>

		</header>
		<!-- Fim header -->

		<!-- Início section agendamentos-finalizados-->
		<section id="agendamentos-finalizados">
			
			<div class="container">
				
				<div class="row mt-5">
					
					<div class="col-12">
						
						<h1 class="display-4">Agendamentos <span class="lead">(Finalizados)</span></h1>
						<hr>

					</div>

				</div>
				<div class="row">
					
					<div class="col-12">
						
						<!-- Card agendamentos em andamento -->
						<div class="card">
							
							<div class="card-body">
								
								<ul class="nav nav-tabs">
									<li class="nav-item">
										<a class="nav-link" href="{{ route('agendamento_andamento')}}">Andamento</a>
									</li>
									<li class="nav-item">
										<a class="nav-link active" href="">Finalizados</a>
									</li>
								</ul>

								<div class="table-responsive mt-4">
									
									<table id="tabelaAgendamentoFinalizado" class="table table-striped">
										<thead>
											<tr>
												<th>#</th>
												<th>Código</th>
												<th>Cliente</th>
												<th>Data</th>
												<th>Horário</th>
												<th>Tipo de lavagem</th>
												<!--<th data-orderable="false"></th>-->
											</tr>
										</thead>
										<tbody>
											@foreach($agendamentos as $agendamento)
											<tr>
												<td></td>
												<td>{{str_pad($agendamento->id, 4, '0', STR_PAD_LEFT)}}</td>
												<td>{{$agendamento->name}}</td>
												<td>{{date('d/m/Y', strtotime($agendamento->data))}}</td>
												<td>{{$agendamento->hora}}</td>
												<td>{{$agendamento->tipo_lavagem}}</td>
												<!--<td>
													<button class="btn btn-sm btn-outline-primary mt-1 mb-1" type="button">
														Foto
													</button>
												</td>-->
											</tr>
											@endforeach
										</tbody>
									</table>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>
		<!-- Fim section agendamentos-finalizados -->

		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<!-- Pacote Bootstrap com Popper -->
		<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
		<!-- DataTables  -->
    	<script src="{{ asset('datatables/datatables.js')}}"></script>
    	<script>
    		$(document).ready( function () {
			    var t = $('#tabelaAgendamentoFinalizado').DataTable({
			        "columnDefs": [ {
			            "searchable": false,
			            "orderable": false,
			            "targets": 0
			        } ],
			        "order": [[ 3, 'asc' ]]
			    });

			    t.on( 'order.dt search.dt', function () {
			        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			            cell.innerHTML = i+1;
			        } );
			    } ).draw();
			} );
    	</script>

	</body>
</html>