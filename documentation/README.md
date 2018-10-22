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
