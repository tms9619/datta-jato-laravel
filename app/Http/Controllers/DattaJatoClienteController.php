<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Agendamento;
use DateTime;
use DateInterval;
use Auth;

class DattaJatoClienteController extends Controller
{
    public function index(){
        return view('cliente');
    }

    public function horarios_disponiveis(Request $request){
        if ($request->ajax()) {
            $output = "";
            $msg = "";
            $cont = "";
            $counter = 0;
            $prox = 0;
            $prev = 0;
            $array_temp = "";
            $array_temp_results = "";

            $duration = 10;
            $start = "09:00";
            $end = "16:10";
    
            $start = new DateTime($start);
            $end = new DateTime($end);
            $interval = new DateInterval("PT".$duration."M");
            $slots = array();
                
            // Armazena todos os intervalos de horas dentro de um array
            for($intStart = $start; $intStart<$end; $intStart->add($interval)){
                    
                $slots[] = $intStart->format("H:i");
                    
            }

            // Consulta no banco horarios já reservados pela data
            $results = DB::table('agendamentos')
                ->where('data', '=', $request->search)
                ->select('agendamentos.hora')
                ->get();

            // Verifica se foi defida uma data
            if ($request->search == ""){
                $msg.='<div class="alert alert-warning" role="alert">'.
                'Informe a data.'
                .'</div>';
                return $msg;
            }

            // Verifica se existe dado
            if ($results != "[]") {

                foreach ($results as $result) {
                    $result_array[] = $result->hora;
                }

                foreach ($slots as $slot) {
                    $prox ++; // controlador que impede que a proxima hora seja a que foi bloqueada

                    // Verifica se valor atual do foreach existe dentro da variavel que guarda horarios marcados
                    if (in_array($slot, $result_array)) {

                        // Consulta o banco e recupera valores de hora(pela data informada) menor ou igual ao valor do foreach atual
                        $temp_results = DB::table('agendamentos')
                        ->where('data', '=', $request->search)
                        ->where('hora', '<=', $slot)
                        ->orderByDesc('hora')
                        ->select('agendamentos.hora')
                        ->limit(3)
                        ->get();

                        $length_array_temp_result = $temp_results->pluck('hora');

                        // Salva em uma lista
                        if($length_array_temp_result->count() == 3){
                            $array_temp = "$length_array_temp_result[0],$length_array_temp_result[1],$length_array_temp_result[2]";
                        }

                        // Pega valor atual do foreach, e prepara uma variavel para comparar com resultado do banco
                        if($slot){
                            $slot_0 = gmdate('H:i', strtotime( $slot ) - strtotime( '00:00' ) );
                            $slot_1 = gmdate('H:i', strtotime( $slot ) - strtotime( '00:10' ) );
                            $slot_2 = gmdate('H:i', strtotime( $slot ) - strtotime( '00:20' ) );
                            $array_slot = "$slot_0,$slot_1,$slot_2";
                        }

                        $length_array_slot = $array_slot;

                        // Bloqueia ultima hora(maior tempo da lista) do retorno do banco que já foi marcada
                        if ($length_array_slot == $array_temp){
                            $cont ++;
                            $counter ++;
                            /*$output.='<div class="col-md-2 col-4">'.
                            '<div class="form-group">'.
                            '<button id="horario'.$cont.'" class="btn btn-outline-danger my-2" type="button" disabled>'.$slot.'</button>'
                            .'</div>'
                            .'</div>';*/
                        }

                        // Verifica se a ultima hora da lista foi bloqueada e bloqueia o proximo horario da lista
                        if ($counter > 0) {
                            $cont ++;
                            $slot_block = gmdate('H:i', strtotime( $slot ) + strtotime( '00:10' ) );
                            /*$output.='<div class="col-md-2 col-4">'.
                            '<div class="form-group">'.
                            '<button id="horario'.$cont.'" class="btn btn-outline-danger my-2" type="button" disabled>'.$slot_block.'</button>'
                            .'</div>'
                            .'</div>';*/
                            $prox = -1; // Impede que a proxima hora seja a que foi bloqueada
                        }

                        // Bloqueia hora já marcada
                        if ($length_array_slot != $array_temp) {
                            $cont ++;
                            /*$output.='<div class="col-md-2 col-4">'.
                            '<div class="form-group">'.
                            '<button id="horario'.$cont.'" class="btn btn-outline-danger my-2" type="button" disabled>'.$slot.'</button>'
                            .'</div>'
                            .'</div>';*/
                            
                        }

                    } else {
                        // Verifica se a comparação que impede o proximo horario foi realizada
                        if($counter > 0){
                            $counter --;
                        }

                        $temp_results = DB::table('agendamentos')
                        ->where('data', '=', $request->search)
                        ->where('hora', '>', $slot)
                        ->orderBy('hora', 'asc')
                        ->select('agendamentos.hora')
                        ->limit(3)
                        ->get();

                        $plunk_results = $temp_results->pluck('hora');

                        // Salva em uma lista
                        if($plunk_results->count() == 3){
                            $array_temp_results = "$plunk_results[0],$plunk_results[1],$plunk_results[2]";
                        }

                        // Pega valor atual do foreach, e prepara uma variavel para comparar com resultado do banco
                        if($slot){
                            $slot_result_0 = gmdate('H:i', strtotime( $slot ) + strtotime( '00:10' ) );
                            $slot_result_1 = gmdate('H:i', strtotime( $slot ) + strtotime( '00:20' ) );
                            $slot_result_2 = gmdate('H:i', strtotime( $slot ) + strtotime( '00:30' ) );
                            $array_slot_result = "$slot_result_0,$slot_result_1,$slot_result_2";
                        }

                        // Bloqueia horario anterior
                        if($array_temp_results == $array_slot_result){
                            $prev = 1;
                            $cont ++;
                            /*$output.='<div class="col-md-2 col-4">'.
                            '<div class="form-group">'.
                            '<button id="horario'.$cont.'" class="btn btn-outline-danger my-2" type="button" disabled>'.$slot.'</button>'
                            .'</div>'
                            .'</div>';*/
                        }

                        // Lista todos os horarios disponiveis
                        if ($prox > 0 && $prev == 0) {
                            $cont ++;
                            $output.='<div class="col-md-2 col-4">'.
                        '<div class="form-group">'.
                        '<button id="horario'.$cont.'" class="btn btn-outline-success my-2" value="'.$slot.'" type="button">'.$slot.'</button>'
                        .'</div>'
                        .'</div>';
                        }

                        // Verifica se foi bloqueado horario anterior
                        if($prev > 0){
                            $prev = 0;
                        }
                        
                    }
                }
            }

            // Verifica se a consulta está vazia
            if ($results == "[]"){
                $result_array[] = "";

                foreach ($slots as $slot) {
                    $cont ++;
                    $output.='<div class="col-md-2 col-4">'.
                    '<div class="form-group">'.
                    '<button id="horario'.$cont.'" class="btn btn-outline-success my-2" value="'.$slot.'" type="button">'.$slot.'</button>'
                    .'</div>'
                    .'</div>';
                }
            }
            
            return Response($output);
        }
    }

    public function store(Request $request){
        try {
            // 72 horas = 4320m
            $union = $request->get('data') . ' ' . $request->get('hora') . ':00';
            $date_now = date('Y-m-d H:i:s');

            $start_date = new DateTime($date_now);
            $since_start = $start_date->diff(new DateTime($union));
            $minutes = $since_start->days * 24 * 60;
            $minutes += $since_start->h * 60;
            $minutes += $since_start->i;

            // Impedir agendamento com mais de 72 horas de antecedência
            if($minutes <= 4320){
                // Verifica se a hora marcada já passou
                if($union > $date_now){
                    $scheduling = new Agendamento;
                    $scheduling->tipo_lavagem = $request->get('tipo_lavagem');
                    $scheduling->data = $request->get('data');
                    $scheduling->hora = $request->get('hora');
                    $scheduling->status = $request->get('status');
                    $scheduling->obs = $request->get('obs');
                    $scheduling->user_id = $request->get('user_id');
                    $scheduling->save();
                    return back()->with('success', 'Seu agendamento foi concluído com sucesso.');
                }
                // Informa ao cliente que o horario informado não pode ser agendado
                if ($union < $date_now) {
                    return back()->with('current_time', 'O horário atual '.date('d/m/Y H:i', strtotime($date_now)).' é maior que o agendamento '.date('d/m/Y H:i', strtotime($union)).', favor informar horário de agendamento maior que o atual.');
                }
            }

            // Informa ao cliente que não pode marca com mais de 72 horas de antecedência
            if($minutes > 4320){
                return back()->with('warning', 'Agendamentos so podem ser feitos com até no maxímo 72 horas de antecedência.');
            }

        }catch(\Exception $e){
            return back()->with('error', 'Falha ao tentar agendar.');
        }
    }

    public function show($id){
        $agendamentos = DB::table('agendamentos')
        ->where('user_id', '=', Auth::user()->id)
        //->orderBy('hora', 'asc')
        ->select('agendamentos.*')
        ->get();
        return view('cliente-agendamentos-realizados', compact('agendamentos'));
    }
}
