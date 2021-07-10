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
					<a class="navbar-brand" href="">
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
									<a class="dropdown-item" href="{{ route('agendamento_finalizado')}}">Finalizado</a>
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

		<!-- Início section principal -->
		<section id="principal">
			
			<div class="container">
				
				<div class="row mt-5">
					
					<div class="col-12">
						
						<h1 class="display-4">Principal</h1>
						<hr>

					</div>

				</div>

			</div>

		</section>
		<!-- Fim section principal -->

		<!-- Início section agendamentos -->
		<section id="agendamentos">
			
			<div class="container">
				
				<div class="row mt-5 mb-3">
					
					<div class="col-12">

						@if(session()->has('success'))
							<div class="alert alert-success">
								{{ session()->get('success') }}
							</div>
						@endif

						@if(session()->has('warning'))
							<div class="alert alert-warning">
								{{ session()->get('warning') }}
							</div>
						@endif

						@if(session()->has('error'))
							<div class="alert alert-danger">
								{{ session()->get('error') }}
							</div>
						@endif
						
						<h2 class="h4">Agendamentos em andamento</h2>
						<!-- Card agendamentos em andamento -->
						<div class="card">
							
							<div class="card-body">

								<div class="table-responsive">
									
									<table id="tabelaAgendamentoAndamento" class="table table-striped">
										<thead>
											<tr>
												<th>#</th>
												<th>Código</th>
												<th>Cliente</th>
												<th>Data</th>
												<th>Horário</th>
												<th data-orderable="false"></th>
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
												<td class="text-center">
													<button class="btn btn-sm btn-outline-success mt-1 mb-1" type="button" data-id="{{$agendamento->id}}" data-bs-toggle="modal" data-bs-target="#Modal">
														Finalizar
													</button>

													<!--<button class="btn btn-sm btn-outline-primary mt-1 mb-1" type="button">
														Foto
													</button>-->
													
													<button class="btn btn-sm btn-outline-danger mt-1 mb-1" type="button" data-id-delete="{{$agendamento->id}}" data-date="{{$agendamento->data}}" data-hora="{{$agendamento->hora}}" data-bs-toggle="modal" data-bs-target="#ModalDelete">
														Excluir
													</button>
												</td>
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
		<!-- Fim section agendamentos -->

		<!-- Modal Finalizar Agendamento-->
		<div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form action="{{ route('user_operador.update', 'update')}}" method="post">
			@csrf
            {{ method_field('PUT') }}
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Finalizar</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input id="user_id" class="form-control" type="hidden" name="id">
								<input id="status" class="form-control" type="hidden" name="status" value="Finalizado">
							</div>
                            <div class="modal-body">
                                <h5>Deseja finalizar o agendamento?</h5>
                            </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Não</button>
					<button type="submit" class="btn btn-outline-success">Sim</button>
				</div>
				</div>
			</form>
		</div>
		</div>

		<!-- Modal Excluir Agendamento-->
		<div class="modal fade" id="ModalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form action="{{ route('user_operador.destroy', 'delete')}}" method="post">
			@csrf
            {{ method_field('DELETE') }}
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Excluir</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input id="user_delete_id" class="form-control" type="hidden" name="id">
								<input id="data_agendamento" class="form-control" type="hidden" name="data">
								<input id="hora_agendamento" class="form-control" type="hidden" name="hora">
							</div>
                            <div class="modal-body">
                                <h5>Deseja excluir o agendamento?</h5>
                            </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Não</button>
					<button type="submit" class="btn btn-outline-success">Sim</button>
				</div>
				</div>
			</form>
		</div>
		</div>

		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<!-- Pacote Bootstrap com Popper -->
		<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
		<!-- DataTables  -->
    	<script src="{{ asset('datatables/datatables.js')}}"></script>
    	<script>
    		$(document).ready( function () {
			    var t = $('#tabelaAgendamentoAndamento').DataTable({
			        "columnDefs": [ {
			            "searchable": false,
			            "orderable": false,
			            "targets": 0
			        } ],
			        "order": [[ 4, 'asc' ]]
			    });

			    t.on( 'order.dt search.dt', function () {
			        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			            cell.innerHTML = i+1;
			        } );
			    } ).draw();
			} );

			// Modal de finalizar
			$('#Modal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Ação do Botão
				var recipientid_agendamento = button.data('id')

				// Recuperando dados do formulario
				var modal = $(this)
				modal.find('#user_id').val(recipientid_agendamento)
            })

			// Modal de excluir
			$('#ModalDelete').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Ação do Botão
				var recipientid_agendamento = button.data('id-delete')
				var recipientdata_agendamento = button.data('date')
				var recipienthora_agendamento = button.data('hora')

				// Recuperando dados do formulario
				var modal = $(this)
				modal.find('#user_delete_id').val(recipientid_agendamento)
				modal.find('#data_agendamento').val(recipientdata_agendamento)
				modal.find('#hora_agendamento').val(recipienthora_agendamento)
            })
    	</script>

	</body>
</html>