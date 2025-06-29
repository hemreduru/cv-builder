<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    /**
     * @var CertificateService
     */
    protected $certificateService;

    /**
     * CertificateController constructor.
     *
     * @param CertificateService $certificateService
     */
    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
        $this->middleware('auth');
        $this->authorizeResource(Certificate::class, 'certificate');
    }

    /**
     * Kullanıcının tüm sertifikalarını listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $certificates = $this->certificateService->getUserCertificates(Auth::id());
        
        return view('certificate.index', compact('certificates'));
    }

    /**
     * Yeni sertifika oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('certificate.create');
    }

    /**
     * Yeni sertifika bilgisini kaydet
     *
     * @param CertificateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CertificateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $certificate = $this->certificateService->createCertificate($data);
            
            Log::info('Certificate created', ['user_id' => Auth::id(), 'certificate_id' => $certificate->id]);
            return redirect()->route('certificates.index')->with('success', __('app.certificate_created'));
        } catch (\Exception $e) {
            Log::error('Error creating certificate', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('certificates.index')->with('error', __('app.certificate_create_error'));
        }
    }

    /**
     * Sertifika düzenleme formunu göster
     *
     * @param Certificate $certificate
     * @return \Illuminate\View\View
     */
    public function edit(Certificate $certificate)
    {
        return view('certificate.edit', compact('certificate'));
    }

    /**
     * Sertifika bilgisini güncelle
     *
     * @param CertificateRequest $request
     * @param Certificate $certificate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CertificateRequest $request, Certificate $certificate)
    {
        try {
            $this->certificateService->updateCertificate($certificate->id, $request->validated());
            
            Log::info('Certificate updated', ['user_id' => Auth::id(), 'certificate_id' => $certificate->id]);
            return redirect()->route('certificates.index')->with('success', __('app.certificate_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating certificate', ['user_id' => Auth::id(), 'certificate_id' => $certificate->id, 'error' => $e->getMessage()]);
            return redirect()->route('certificates.index')->with('error', __('app.certificate_update_error'));
        }
    }

    /**
     * Sertifika bilgisini sil
     *
     * @param Certificate $certificate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Certificate $certificate)
    {
        try {
            $this->certificateService->deleteCertificate($certificate->id);
            
            Log::info('Certificate deleted', ['user_id' => Auth::id(), 'certificate_id' => $certificate->id]);
            return redirect()->route('certificates.index')->with('success', __('app.certificate_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting certificate', ['user_id' => Auth::id(), 'certificate_id' => $certificate->id, 'error' => $e->getMessage()]);
            return redirect()->route('certificates.index')->with('error', __('app.certificate_delete_error'));
        }
    }
}
