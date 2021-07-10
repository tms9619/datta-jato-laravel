<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Agendamento;
use DateTime;
use App\User;

class DattaJatoOperadorController extends Controller
{
    public function index(){
        $agendamentos = DB::table('agendamentos')
        ->join('users', 'agendamentos.user_id', 'users.id')
        ->where('agendamentos.status', '=', 'Andamento')
        ->select('agendamentos.*', 'users.name')
        ->get();
        return view('operador', compact('agendamentos'));
    }

    public function agendamento_andamento(){
        $agendamentos = DB::table('agendamentos')
        ->join('users', 'agendamentos.user_id', 'users.id')
        ->where('agendamentos.status', '=', 'Andamento')
        ->select('agendamentos.*', 'users.name')
        ->get();
        return view('operador-agendamentos-andamento', compact('agendamentos'));
    }

    public function agendamento_finalizado(){
        $agendamentos = DB::table('agendamentos')
        ->join('users', 'agendamentos.user_id', 'users.id')
        ->where('agendamentos.status', '=', 'Finalizado')
        ->select('agendamentos.*', 'users.name')
        ->get();
        return view('operador-agendamentos-finalizado', compact('agendamentos'));
    }

    public function update(Request $request){
        try {
            $agendamento = Agendamento::findOrFail($request->get('id'));
            $agendamento->status = $request->get('status');
            $agendamento->update();
            return back()->with('success', 'Agendamento "'.str_pad($request->get('id'), 4, '0', STR_PAD_LEFT).'" finalizado com sucesso.');
        }catch(\Exception $e){
            return back()->with('error', 'Ocorreu um erro ao tentar finalizar o agendamento '.str_pad($request->get('id'), 4, '0', STR_PAD_LEFT).'.');
        }
    }

    public function destroy(Request $request){
        try {
            // 6 horas = 360m
            $union = $request->get('data') . ' ' . $request->get('hora') . ':00';
            $date_now = date('Y-m-d H:i:s');
            $h_now = date('H:i');
            
            $start_date = new DateTime($date_now);
            $since_start = $start_date->diff(new DateTime($union));
            $minutes = $since_start->days * 24 * 60;
            $minutes += $since_start->h * 60;
            $minutes += $since_start->i;

            // Verifica hora atual e compara com a do agendamento para saber se há pelemenos 6 horas de diferença
            if($minutes >= 360){
                $agendamento = Agendamento::findOrFail($request->get('id'));
                $agendamento->delete();
                return back()->with('success', 'Agendamento "'.str_pad($request->get('id'), 4, '0', STR_PAD_LEFT).'" excluído com sucesso.');
            }

            // Impede exclusão do dado caso ajá menos de 6 horas de diferença da hora atual
            if($minutes < 360){
                return back()->with('warning', 'Ocorreu um erro ao tentar excluir o agendamento "'.str_pad($request->get('id'), 4, '0', STR_PAD_LEFT).'" só é possivel excluir agendamentos que possuam no minímo 6 horas de antecedência ou já foram concluídos.');
            }

        }catch(\Exception $e){
            return back()->with('error', 'Ocorreu um erro ao tentar excluir o agendamento '.str_pad($request->get('id'), 4, '0', STR_PAD_LEFT).'.');
        }
    }
}
