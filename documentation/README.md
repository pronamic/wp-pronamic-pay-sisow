# Sisow Documentation

## Parameter `locale`

> locale » Voor de taal van de betaalpagina

**Question Pronamic** on **12 okt. 2018 14:45**:

> Welke waardes zijn precies toegestaan voor de `locale` parameter? Bijvoorbeeld:
> 
> - en
> - EN
> - en_US
> - en_GB

**Answer Sisow** on **18 okt. 2018 12:47**:

> Hieronder vind je de waardes voor de locale parameter.
> 
> Locale paypal:  
> `array('AU','AT','BE','BR','CA','CH','CN','DE','ES','GB','FR','IT','NL','PL','PT','RU','US');`
> 
> locale cc en mistercash  
> `array('NL', 'BE', 'DE', 'IT', 'ES', 'PT', 'BR', 'SE', 'FR');`

## Parameter `product_id_x` for `shipping` and `paymentfee`

> Voor de verzendkosten en een eventuele betaaltoeslag door te geven kunnen de volgende product_id_x worden gebruikt:
> - shipping, doorgeven van de verzendkosten
> - paymentfee, doorgeven van een eventuele betaaltoeslag

**Question Pronamic** on **12 okt. 2018 14:45**:

> Klopt het dat dit er als volgt uit moet gaan zien?

```
product_id_1=123
product_description_1=Test
product_id_2=shipping
product_description_2=Verzending
product_id_3=paymentfee
product_description_3=Betalingskosten
```

> Is het daarbij ook mogelijk om meerdere `shipping` en `paymentfee` producten door te geven? En kunnen de bedragen bij een `paymentfee` product ook negatief zijn?

**Answer Sisow** on **18 okt. 2018 12:47**:

> Het bovenstaande klopt inderdaad, regels shipping en payment fee mogen meerdere keren voorkomen, en bedragen mogen negatief zijn.

## Achteraf betalen testen

**Question Pronamic** on **18 okt. 2018 14:59**:

> Hoe kunnen we jullie achteraf betalen methodes nu gaan testen?

**Answer Sisow** on **19 okt. 2018 16:15**:

> Ik heb intern het een en ander moeten navragen of het testen wel mogelijk is.
> Alleen een Afterpay hebben wij voor u kunnen implementeren.
> Een Focum test omgeving is bij ons (nog) niet geïmplementeerd, en bij Klarna wordt er nog gebruik gemaakt van een oude API waarbij geen nieuwe klanten op toegelaten.

## Error `TA3450` `Reservation not possible (Failed;General)`

**Question Pronamic** on **22 okt. 2018 09:48**:

> Bedankt voor je reactie, we zijn inmiddels verder gegaan met het testen van AfterPay. We krijgen momenteel echter het volgende antwoord van Sisow:

```
<?xml version="1.0" encoding="UTF-8"?><errorresponse xmlns="https://www.sisow.nl/Sisow/REST" version="1.0.0"><error><errorcode>TA3450</errorcode><errormessage>Reservation not possible (Failed;General)</errormessage></error></errorresponse>
```

> De foutmelding is helaas niet heel duidelijk en wij kunnen daarom helaas niet achterhalen wat er precies fout gaat. Ook is jullie documentatie hier niet heel duidelijk over:

```
TA3450 » Reservation not possible » TransactionRequest » De reservering van het opgegeven bedrag is niet gelukt
```

> Hoe kunnen we achterhalen wat er fout gaat? Misschien sowieso goed om de foutcode en documentatie uit te breiden met de reden waarom het niet gelukt is?

**Answer Sisow** on **22 okt. 2018 10:23**:

> Onderstaande foutmelding krijg je te zien als de transactie niet voorbij de credietcheck van AfterPay gaat.  
> Heeft u tijdens het afrekenen legitieme gegevens gebruik?  
> Wanneer u gegevens invult die niet kloppen, wijst AfterPay deze transactie af.  

**Question Pronamic** on **23 okt. 2018 14:52**:

> Waarschijnlijk werd `billing_phone` en `shipping_phone` nog niet meegegeven, dit zullen we nog gaan testen. Los daarvan is de betreffende foutmelding wel vrij mager, hoe kunnen we gebruikers op deze manier goed informeren over waar het fout gaat? Als we naar de AfterPay documentatie zelf kijken geven zij wel uitgebreide informatie terug? 
> 
> https://developer.afterpay.io/guidelines/prepare-check-out#consumer-info
> 
> We zijn wel benieuwd naar hoe we hier mee om moeten gaan, we horen het graag!

**Answer Sisow** on **24 okt. 2018 08:59**:

> Wij krijgen vanuit Afterpay alleen de algemene melding mee dat een reservering niet is gelukt.  
> De specifieke reden krijgen wij niet mee, anders hadden wij dit ook kunnen vermelden.  
> De melding TA3450 houdt dus in dat deze transactie niet door de credietcheck van AfterPay is gekomen.  
> Vaak ontvangen consumenten/merchants deze melding op het moment wanneer geen legitieme gegevens worden gebruikt.  
> Vandaar is het aan te raden om te controleren of er daadwerkelijk kloppende, echte gegevens worden ingevuld op de betaalpagina.

## Integreer onze nieuwe betaalmethoden Billink

**Question Pronamic** on **7 nov. 2018 10:59**:

> We kregen jullie "Integreer onze nieuwe betaalmethoden Billink" nieuwsbrief binnen. Kan het kloppen dat de API documentatie nog niet helemaal up-to-date is? In "Bijlage 1: payment values" zien we namelijk "Billink" niet staan:
> 
> Verder vroegen we ons ook af of we Billink betalingen ook kunnen testen/simuleren. We horen het graag, alvast bedankt.

**Answer Sisow** on **8 nov. 2018 08:29**:

> Bedankt voor de constatering, we zijn deze inderdaad vergeten.
> De code die je kunt gebruiken is “billink”.

## Error `TA9990` `Gothia`

**Question Pronamic** on **7 nov. 2018 11:06**:

> We zijn momenteel bezig met de implementatie van AfterPay via Sisow. We hebben hier eerder ook contact over gehad. Inmiddels lukt het om een AfterPay test betaling op te starten. We zijn nu bezig met de implementatie van `InvoiceRequest`, `CancelReservationRequest`, etc. We lopen echter tegen problemen aan. Als we `CancelReservationRequest` uitvoeren met de volgende parameters:

```
[merchantid] => 2537541595
[trxid] => TEST080531390587
[sha1] => 0f1b601f4fb1936463ea216376f1fda9041a778c
```

> Krijgen we de volgende foutmelding terug:

```xml
<?xml version="1.0" encoding="UTF-8"?><errorresponse xmlns="https://www.sisow.nl/Sisow/REST" version="1.0.0"><error><errorcode>TA9990</errorcode><errormessage>Gothia</errormessage></error></errorresponse>
```

> Kunnen jullie achterhalen wat we fout doen en wat foutcode `TA9990` en foutmelding `Gothia` precies betekenen?

**Answer Sisow** on **14 nov. 2018 08:23**:

> Het was even zoeken maar onderstaande is opgelost.  
> De gegeven foutmelding zou ook niet meer voor mogen komen.
