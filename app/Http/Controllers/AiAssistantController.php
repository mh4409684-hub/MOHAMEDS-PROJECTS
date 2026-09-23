<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    /**
     * Respond to user query using MohamedTech Pro AI Assistant logic
     */
    public function ask(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $query = mb_strtolower(trim($validated['message']));

        $reply = $this->generateAnswer($query);

        return response()->json([
            'status' => 'success',
            'reply' => $reply,
            'powered_by' => 'MOHAMEDYTECH PRO AI'
        ]);
    }

    /**
     * Intelligent knowledge base matching
     */
    private function generateAnswer(string $q): string
    {
        // 1. Contact / Mawasiliano
        if (str_contains($q, 'wasiliana') || str_contains($q, 'mawasiliano') || str_contains($q, 'contact') || str_contains($q, 'namba') || str_contains($q, 'simu') || str_contains($q, 'email') || str_contains($q, 'msaada wa haraka')) {
            return "📞 *Mawasiliano Rasmi na Msaada:*\n\n"
                 . "👤 *Eng. MOHAMEDY HAMADI MOHAMED (Portal Owner & Tech Lead)*\n"
                 . "📱 WhatsApp / Simu: *+255 777 568 288* au *+255 628 359 873*\n"
                 . "📧 Barua Pepe: *mh4409684@gmail.com*\n"
                 . "🏫 Ofisi: College of Business Education (CBE) - ICT & Field Directorate.\n\n"
                 . "💬 Unaweza kubonyeza kitufe cha WhatsApp chini kuwasiliana nasi papo hapo!";
        }

        // 2. Usajili / Kujisajili
        if (str_contains($q, 'sajili') || str_contains($q, 'register') || str_contains($q, 'akaunti mpya') || str_contains($q, 'jiunga')) {
            return "📝 *Jinsi ya Kujisajili (Student Registration):*\n\n"
                 . "1. Bonyeza linki ya 'Click Here to Register New Student Account' kwenye ukurasa wa kuingia au nenda moja kwa moja `/cbe/register`.\n"
                 . "2. Jaza jina kamili, Registration Number yako ya CBE, Email, Simu, Campus, na Chagua Kozi yako.\n"
                 . "3. Weka password yenye herufi 6 au zaidi kisha bonyeza Submit.\n"
                 . "4. Utapokea ujumbe kwenye email na WhatsApp yako mara moja kuthibitisha maombi yako yanapopokelewa.";
        }

        // 3. Password / Kusahau nenosiri
        if (str_contains($q, 'password') || str_contains($q, 'nenosiri') || str_contains($q, 'sahau') || str_contains($q, 'forgot') || str_contains($q, 'reset')) {
            return "🔐 *Umesahau Nenosiri? (Reset Password):*\n\n"
                 . "1. Nenda kwenye ukurasa wa Login kisha bonyeza 'Forgot password?' au tembelea `/cbe/forgot-password`.\n"
                 . "2. Ingiza Username na Barua Pepe yako uliyojisajili nayo.\n"
                 . "3. Mfumo utakutumia OTP ya tarakimu 6 kwenye Barua Pepe yako.\n"
                 . "4. Jaza OTP hiyo pamoja na nenosiri lako jipya kisha bonyeza 'Save & Reset Password'.\n\n"
                 . "💡 *Kama huoni email ya OTP:* Hakikisha unakagua pia folda ya **Spam / Junk** kwenye email yako, au wasiliana nasi WhatsApp *+255 777 568 288*.";
        }

        // 3b. Email / Ujumbe / OTP Kutofika
        if (str_contains($q, 'email') || str_contains($q, 'barua pepe') || str_contains($q, 'otp') || str_contains($q, 'ujumbe') || str_contains($q, 'mail')) {
            return "📧 *Kuhusu Kupokea Barua Pepe (Email & OTP):*\n\n"
                 . "Mfumo hutuma barua pepe za uthibitisho na OTP moja kwa moja kwenye barua pepe yako:\n"
                 . "1. **Kagua Inbox na Spam:** Mara nyingine barua pepe huenda kwenye folda ya **Spam** au **Junk** ya Gmail/Yahoo/Outlook yako.\n"
                 . "2. **Anwani Sahihi:** Hakikisha barua pepe uliyoandika haina herufi iliyokosewa.\n"
                 . "3. **Msaada wa Papo Hapo:** Ikiwa bado hujaipokea, wasiliana na Eng. Mohamedy Hamadi moja kwa moja WhatsApp: *+255 777 568 288* ili akufanyie reset au akusaidie papo hapo!";
        }

        // 3c. Tatizo / Shida / Help
        if (str_contains($q, 'shida') || str_contains($q, 'tatizo') || str_contains($q, 'kero') || str_contains($q, 'haifanyi') || str_contains($q, 'tatizo') || str_contains($q, 'help') || str_contains($q, 'msaada')) {
            return "🛠️ *Utatuzi wa Matatizo na Shida za Kawaida:*\n\n"
                 . "• **Kushindwa Kuingia (Login Failed):** Hakikisha unatumia Username au Reg Number sahihi na Password yako.\n"
                 . "• **Akaunti Inasubiri Idhini:** Ukishajisajili, akaunti yako inahitaji kuhakikiwa na Admin kabla ya kuingia.\n"
                 . "• **GPS ya Mahudhurio:** Hakikisha umewasha Location kwenye simu yako na umeruhusu browser kuona eneo lako (Allow).\n"
                 . "• **Wasiliana Nasi Moja kwa Moja:** Bofya kitufe cha WhatsApp chini au piga *+255 777 568 288* kupata msaada wa haraka!";
        }

        // 4. Field Placement / Eneo la Field
        if (str_contains($q, 'field') || str_contains($q, 'sehemu') || str_contains($q, 'taasisi') || str_contains($q, 'placement') || str_contains($q, 'organization') || str_contains($q, 'msimamizi')) {
            return "🏢 *Kujaza au Kubadili Eneo la Field:*\n\n"
                 . "1. Ingia kwenye akaunti yako ya mwanafunzi (Student Dashboard).\n"
                 . "2. Kwenye dashibodi, bonyeza kitufe cha: *'Jaza Eneo la Field Ulilopata Sasa Hivi'* au *'Badili Taarifa za Field'*.\n"
                 . "3. Jaza jina la taasisi/kampuni, mji/mkoa, sekta ya kazi, anwani kamili, na namba ya simu ya Host Supervisor wako kazini.\n"
                 . "4. Bonyeza Hifadhi ili taarifa zako zirekodiwe mara moja tayari kwa GPS Attendance na E-Logbook!";
        }

        // 5. GPS Attendance / Mahudhurio
        if (str_contains($q, 'attendance') || str_contains($q, 'mahudhurio') || str_contains($q, 'gps') || str_contains($q, 'check in') || str_contains($q, 'checkin') || str_contains($q, 'eneo')) {
            return "📍 *GPS Attendance (Mahudhurio ya Field):*\n\n"
                 . "1. Fungua ukurasa wa 'GPS Attendance' kwenye menyu ya mwanafunzi.\n"
                 . "2. Hakikisha simu/kompyuta yako imewashwa Location/GPS na umetoa ruhusa ya browser (Allow Location).\n"
                 . "3. Mfumo utapima eneo lako la sasa kwa setilaiti kuhakikisha upo ndani ya mita 300 za taasisi yako ya field.\n"
                 . "4. Bonyeza kitufe cha kijani cha 'Confirm GPS Check-In' kuweka mahudhurio ya siku.";
        }

        // 6. E-Logbook / Shughuli za kila siku
        if (str_contains($q, 'logbook') || str_contains($q, 'kitabu') || str_contains($q, 'ripoti') || str_contains($q, 'shughuli') || str_contains($q, 'report') || str_contains($q, 'grading')) {
            return "📖 *Kujaza E-Logbook na Ripoti:* \n\n"
                 . "1. Kwenye menyu ya mwanafunzi chagua 'E-Logbook' kisha bonyeza 'New Log Entry'.\n"
                 . "2. Andika shughuli ulizozifanya kwa siku, ujuzi uliopata (skills gained), na changamoto ulizokutana nazo.\n"
                 . "3. Bonyeza Submit ili msimamizi wako (Supervisor) akague na kuidhinisha (Approve) kazi zako kwa ajili ya alama za chuo.";
        }

        // 7. General Greetings / Default Fallback
        if (str_contains($q, 'habari') || str_contains($q, 'mambo') || str_contains($q, 'hello') || str_contains($q, 'hi') || str_contains($q, 'hey')) {
            return "👋 *Habari! Mimi ni MohamedTech Pro AI Assistant wa CBE Portal.*\n\n"
                 . "Nipo hapa kukuelekeza na kukusaidia papo hapo kuhusu:\n"
                 . "• Usajili wa wanafunzi (Registration)\n"
                 . "• Kuingia na kubadili nenosiri lililosahaulika (Password Reset)\n"
                 . "• Kujaza na kubadili eneo la field na supervisor kazini\n"
                 . "• GPS Attendance na kujaza E-Logbook\n"
                 . "• Kuwasiliana na timu ya msaada na mmiliki wa mfumo.\n\n"
                 . "👉 *Niambie unahitaji msaada gani sasa hivi?*";
        }

        // Fallback with Direct Support
        return "🤖 *MohamedTech Pro AI Guide:*\n\n"
             . "Nimepokea swali lako kuhusu: *\"{$q}\"*\n\n"
             . "Kama unahitaji mwongozo au unakabiliwa na tatizo lolote la kiufundi:\n"
             . "1. **Usajili au Kuingia:** Hakikisha unatumia Registration Number na nenosiri sahihi.\n"
             . "2. **GPS / Mahudhurio:** Washa Location kwenye simu yako na ubonyeze 'Allow' browser inapoomba eneo.\n"
             . "3. **Msaada wa Moja kwa Moja:** Wasiliana na Eng. Mohamedy kupitia WhatsApp: *+255 777 568 288* au barua pepe: *mh4409684@gmail.com*.";
    }
}
