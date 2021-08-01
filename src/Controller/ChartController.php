<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartController extends AbstractController
{
    /**
     * @Route("/chart/index", name="chart")
     */
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $labels = [];
        $datasets = [];
         $decl1="select avg(round(result.total)) as avg,
         concat(subject.name,subject_year.syear) as name
         from result inner join result_subject_year on
         result.id=result_subject_year.result_id inner join subject_year
         on result_subject_year.subject_year_id=subject_year.id
         inner join subject on subject_year.subjectname_id=subject.id
         group by result_subject_year.subject_year_id order by avg desc";

        $statement121=$connection->prepare($decl1);
        $statement121->execute();
        $repo=$statement121->fetchAll();
        foreach($repo as $data){
            $labels[] = $data['name'];
            $datasets[] = $data['avg'];
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'average performance',
                    'backgroundColor' =>  [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                     ] ,
                    'borderColor' => 'purple',
                    'fill'=> false,
                    'borderWidth'=> 4,
                    'data' => $datasets,
                ],
            ],
        ]);

        $chart->setOptions([
         'animation'=>[
        'tension'=> [
        'duration'=>1000,
        'easing'=> 'linear',
        'from'=> 1,
        'to'=> 0,
        'loop'=> true,
      ]
        ],
        'legend'=>[
            'position'=>"bottom",

        ],
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 40,'beginAtZero'=>true,
                    'fontColor'=> "black",
                    'fontStyle'=> "bold",]
                    ],
                ],
                'xAxes' => [
                    ['ticks' => ['beginAtZero'=>true,
                    'fontColor'=> "black",
                    'fontStyle'=> "bold",]],
                ],
            ],
        ]);
        return $this->render('chart/index.html.twig', [
            'controller_name' => 'ChartController','chart'=>$chart
        ]);
    }

}
