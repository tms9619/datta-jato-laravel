<!DOCTYPE html>
<html lang="pt-br">
	<head>

		<!-- Meta tags obrigatórias -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="_token" content="{{ csrf_token() }}">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">

		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css')}}">

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
									<i class="fas fa-calendar-alt"></i> Meus agendamentos
								</a>
								
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="{{ route('user_cliente.show', 'show')}}">Realizados</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="">Novo</a>
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

		<!-- Início section novo-agendamento -->
		<section id="novo-agendamento">
			
			<div class="container">
				
				<div class="row mt-5">
					
					<div class="col-12">
						
						<h1 class="display-4">Novo agendamento</h1>
						<hr>

					</div>

				</div>
				<div class="row">
					
					<div class="col-md-6 mx-auto">
						
						<!-- Início card novo agendamento -->
						<div class="card">
							
							<div class="card-body">
								
								<div class="row">

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

									@if(session()->has('current_time'))
										<div class="alert alert-warning">
											{{ session()->get('current_time') }}
										</div>
									@endif

									@if(session()->has('error'))
										<div class="alert alert-danger">
											{{ session()->get('error') }}
										</div>
									@endif
											
										<div class="form-group">
												
											<label for="data-agendamento">Tipo de lavagem:</label>
											<select id="tipo-lavagem-valor" class="form-select form-select-sm mb-3" autofocus="autofocus">
												<option value="Completa" selected>Completa</option>
												<option value="Externa">Externa</option>
											</select>

										</div>

									</div>

								</div>
									
								<div class="row">
										
									<div class="col-md-6">
											
										<div class="form-group">
												
											<label for="data-agendamento">Escolha a data:</label>
											<input id="data-agendamento-valor" type="date" min="" placeholder="Data*" required="required" type="text">

										</div>

									</div>

									<div class="col-md-6">
											
										<div class="form-group">
												
											<button id="data-horario" class="btn btn-sm btn-primary" type="button">Pesquisar horário</button>

										</div>

									</div>

								</div>

                                <br/>

								<div id="div-show" style="display: none;">
									<h1 class="display-4 fs-3">Horários disponíveis</h1>
									<hr/>
								</div>

                                <div id="listar" class="row">
                                    <!-- Exibi automaticamento horarios disponiveis após selecionar data -->
                                </div>

							</div>

						</div>
						<!-- Fim card novo agendamento -->

					</div>

				</div>

			</div>

		</section>
		<!-- Fim section novo-agendamento -->

		<!-- Modal -->
		<div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form action="{{ route('user_cliente.store')}}" method="post">
			@csrf
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Agendar para <span id="horario-title"></span></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input id="tipo-lavagem" class="form-control" type="hidden" name="tipo_lavagem">
								<input id="data" class="form-control" type="hidden" name="data">
								<input id="horario" class="form-control" type="hidden" name="hora">
								<input id="status" class="form-control" type="hidden" name="status" value="Andamento">
								<input id="user_id" class="form-control" type="hidden" name="user_id" value="{{Auth::user()->id}}">
							</div>
							<div class="form-group">
								<textarea id="observacao" class="form-control" placeholder="Observação" autofocus="autofocus" type="text" name="obs" rows="5" style="resize: none;"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Escolher outro horário</button>
					<button type="submit" class="btn btn-outline-success">Agendar</button>
				</div>
				</div>
			</form>
		</div>
		</div>

		<!-- Pacote Bootstrap com Popper -->
		<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('js/jquery.min.js')}}"></script>
		<script src="{{ asset('js/jquery.easing.min.js')}}"></script>

        <script type="text/javascript">
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        </script>

        <script>

		// Pegar data atual
		var data = new Date();
		var ano = data.getFullYear(); // Ano
		var mes = data.getMonth(); // Mês
		var dia = data.getDate(); // Dia
		mes ++

		if(mes >= 10 && dia >= 10){
			var data_atual_min = ano + '-' + mes + '-' + dia // Data atual preencher att min
		}

		if(mes < 10 && dia < 10){
			var data_atual_min = ano + '-' + '0' + mes + '-' + '0' + dia // Data atual preencher att min
		}

		if(mes >= 10 && dia < 10){
			var data_atual_min = ano + '-' + mes + '-' + '0' + dia // Data atual preencher att min
		}

		if(mes < 10 && dia >= 10){
			var data_atual_min = ano + '-' + '0' + mes + '-' + dia // Data atual preencher att min
		}

		var data_min = document.getElementById("data-agendamento-valor")
		data_min.setAttribute("min", data_atual_min)

        // Ajax exibir todos os horarios disponiveis
        $("#data-horario").click(function(){
            var $date = document.getElementById('data-agendamento-valor').value;
			if ($date != ""){
				document.getElementById('div-show').style.display = 'block';
			}
            $.ajax({
                type : 'get',
                url : '{{URL::to('horarios')}}',
                data:{'search':$date},
                dataType: "html",
                success:function(data){
                $('#listar').html(data);
                }
            });
        }); 

		// Recupera dados para modal de confirmação
		var div = document.getElementById("listar");
			div.addEventListener('click', function(e) {
				var id = e.target.id;
				var horario = e.target.value
				if (horario === undefined){
					console.log("Variavel vazia.")
				}else{
					var tipo_lavagem = document.getElementById('tipo-lavagem-valor').value
					var data_lavagem = document.getElementById('data-agendamento-valor').value
					$("#horario-title").html(horario);
					$("#tipo-lavagem").val(tipo_lavagem);
					$("#data").val(data_lavagem);
					$("#horario").val(horario);
					$("#Modal").modal("show");
				}
			});

        </script>

	</body>
</html>