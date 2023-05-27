<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridApiTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport as MailerTransport;
use Symfony\Component\Mailer\Transport\SendmailTransport;

class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company', methods: ['GET', 'POST'])]
    public function index(Request $req, MailerInterface $mailer0): Response
    {
        $company = new Company();
        $company_form = $this->createForm(CompanyType::class, $company);
        $postdata = $req->request->all();
        // dd($postdata);
        if (!empty($postdata['company'])) {
            $postinfo = $postdata['company'];
            $_symbol = $postinfo['symbol'];
            $_to =  $postinfo['email'];
            $_startdate = $postinfo['startdate'];
            $_enddate = $postinfo['enddate'];

            $rangedprices = [];
            $data_stats = [];

            //Validations
            $valid = $this->validation($postinfo);


            if ($valid) {

                $fetchcoldata = Company::fetchsymboldata('Company Name', $_symbol);
                $_compname = (isset($fetchcoldata[$_symbol])) ? $fetchcoldata[$_symbol] : '';

                //Send Email
                $mail_subject = $_compname;
                $mail_body = "$_startdate To $_enddate";
                $email = (new Email())
                    ->from('xmproject@xmproj.com')
                    ->to($_to)
                    ->subject($mail_subject)
                    ->text($mail_body);
                $mailer0->send($email);

                $this->addFlash('success', 'email has been sent successfully!');


                $hist_recs = $this->historical_data($_symbol);
                $prices = $hist_recs['prices'];

                if (!empty($prices)) {
                    for ($x = 0; $x < count($prices); $x++) {
                        $timestamp = (int)$prices[$x]['date'];
                        $date = new DateTime();
                        $date->setTimestamp($timestamp);
                        $setdate = $date->format('Y-m-d');
                        if (($setdate >= $_startdate) && ($setdate <= $_enddate)) {
                            $xm = count($rangedprices);
                            $rangedprices[$xm]['open'] = (isset($prices[$x]['open'])) ? $prices[$x]['open'] : 0;
                            $rangedprices[$xm]['close'] = (isset($prices[$x]['close'])) ? $prices[$x]['close'] : 0;
                            $rangedprices[$xm]['high'] = (isset($prices[$x]['high'])) ? $prices[$x]['high'] : 0;
                            $rangedprices[$xm]['low'] = (isset($prices[$x]['low'])) ? $prices[$x]['low'] : 0;
                            $rangedprices[$xm]['volume'] = (isset($prices[$x]['volume'])) ? $prices[$x]['volume'] : 0;
                            $rangedprices[$xm]['date'] = $setdate;
                            $data_stats['labels'][] = "open ({$setdate})";
                            $data_stats['labels'][] = "close ({$setdate})";
                            $data_stats['values'][]  = ['open' => $rangedprices[$xm]['open'], 'close' => $rangedprices[$xm]['close']];
                            $data_stats['backcolors'][]  = 'rgba(54, 162, 235, 0.2)';
                            $data_stats['backcolors'][]  = 'rgba(255, 99, 132, 0.2)';
                            $data_stats['bordcolors'][]  = 'rgb(54, 162, 235)';
                            $data_stats['bordcolors'][]  = 'rgb(255, 99, 132)';
                        }
                    }
                }
            }

            // dd($data_stats);
            return $this->render('company/index.html.twig', [
                'company_form' => $company_form->createView(),
                'prices' => $rangedprices,
                'stats' => $data_stats
            ]);
        }

        return $this->render('company/index.html.twig', [
            'company_form' => $company_form->createView(),
            'prices' => [],
            'stats' => []
        ]);
    }

    public function historical_data($symbol, $region = 'US')
    {
        $url = "https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data?symbol={$symbol}&region={$region}";
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-RapidAPI-Key: ' . $this->getParameter('app.xrapid_apikey'),
                'X-RapidAPI-Host: ' . $this->getParameter('app.xrapid_host')
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        // echo "url: $url";
        $output = curl_exec($ch);
        echo curl_error($ch);
        $result = json_decode($output, true);
        // dd($result);

        return $result;
    }

    private function validation($postdata): bool
    {
        $_email = $postdata['email'];
        $_startdate = $postdata['startdate'];
        $_enddate = $postdata['enddate'];
        $_symbol = $postdata['symbol'];
        $symbol_list = Company::getcompcolumn_list('Symbol', true);

        $valid = true;
        if ($_startdate > $_enddate) {
            $valid = false;
            $this->addFlash('failure', "start date can't be greater than end date");
        }

        if (!in_array($_symbol, $symbol_list)) {
            $valid = false;
            $this->addFlash('failure', "invalid company symbol");
        }

        if (!filter_var($_email, FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $this->addFlash('failure', "invalid email address");
        }

        return $valid;
    }
}
