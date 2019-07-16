<?php declare(strict_types=1);

namespace Shwrm\Tracking\Enum;

final class DPDBusinessCodes
{
    const NEW        = 'new';
    const DELIVERED  = 'delivered';
    const RETURNED   = 'returned';
    const ERROR      = 'error';
    const SENT       = 'sent';
    const REDIRECTED = 'redirected';

    public static function all(): array
    {
        return [
            '120100' => self::SENT, // package.adoption.at.dpd.depot
            '110302' => self::SENT, // package.repackaging
            '120101' => self::SENT, // package.adoption.at.dpd.depot
            '120102' => self::SENT, // package.adoption.at.dpd.depot
            '120103' => self::SENT, // package.adoption.at.dpd.depot
            '120104' => self::SENT, // package.adoption.at.dpd.depot
            '150301' => self::SENT, // package.repackaging
            '160101' => self::SENT, // package.adoption.at.dpd.depot
            '160102' => self::SENT, // sorting.error
            '160103' => self::SENT, // package.adoption.at.dpd.depot
            '160202' => self::SENT, // sorting.error
            '160501' => self::SENT, // package.adoption.at.dpd.depot
            '160502' => self::SENT, // package.adoption.at.dpd.depot
            '160503' => self::SENT, // package.adoption.at.dpd.depot
            '170101' => self::SENT, // package.released.to.delivery
            '170102' => self::SENT, // package.released.to.delivery
            '170205' => self::SENT, // sorting.error
            '170302' => self::SENT, // package.repacked
            '170304' => self::SENT, // sent.notification
            '170305' => self::SENT, // sent.notification
            '170306' => self::SENT, // sent.notification
            '170307' => self::SENT, // sent.notification
            '170308' => self::SENT, // sent.notification
            '170501' => self::SENT, // package.dispatched.at.pickup.point
            '190101' => self::DELIVERED, // package.delivered
            '190102' => self::DELIVERED, // package.delivered
            '190103' => self::DELIVERED, // package.delivered
            '190104' => self::DELIVERED, // package.delivered
            '190105' => self::DELIVERED, // package.delivered
            '190201' => self::DELIVERED, // package.delivered
            '190202' => self::DELIVERED, // package.delivered
            '190203' => self::DELIVERED, // package.delivered
            '190204' => self::DELIVERED, // package.delivered
            '190205' => self::DELIVERED, // package.delivered
            '200201' => self::SENT, // package.not.delivered.receiver.absent
            '200202' => self::SENT, // package.not.delivered.receiver.absent
            '200203' => self::SENT, // package.not.delivered.receiver.absent
            '200204' => self::SENT, // package.not.delivered.receiver.absent
            '200205' => self::SENT, // package.not.delivered.receiver.absent
            '200206' => self::SENT, // package.not.delivered.receiver.absent
            '200300' => self::SENT, // package.not.delivered.incorrect.address
            '200301' => self::SENT, // package.not.delivered.incorrect.address
            '200302' => self::SENT, // package.not.delivered.incorrect.address
            '200303' => self::SENT, // package.not.delivered.incorrect.address
            '200304' => self::SENT, // package.not.delivered.incorrect.address
            '200305' => self::SENT, // package.not.delivered.incorrect.address
            '200306' => self::SENT, // package.not.delivered.incorrect.address
            '200307' => self::SENT, // package.not.delivered.incorrect.address
            '200308' => self::SENT, // package.not.delivered.incorrect.address
            '200309' => self::SENT, // package.not.delivered.incorrect.address
            '200310' => self::SENT, // package.not.delivered.incorrect.address
            '200400' => self::SENT, // package.not.delivered.refusal
            '200401' => self::SENT, // package.not.delivered.lack.of.cod.cash
            '200402' => self::SENT, // package.not.delivered.receiver.resigned
            '200403' => self::SENT, // package.not.delivered.telephone.resignation
            '200404' => self::SENT, // package.not.delivered.article.not.ordered
            '200405' => self::SENT, // package.not.delivered.cod.payment.refuse.bad.payment.type
            '200406' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '200408' => self::SENT, // package.not.delivered.refuse.lack.of.exw.cash
            '200409' => self::SENT, // package.not.delivered.refuse.bad.payment.type.exw
            '200410' => self::SENT, // package.not.delivered.refuse.incorrect.exw.amount
            '200411' => self::SENT, // package.not.delivered.refuse
            '200412' => self::SENT, // package.not.delivered.refuse.lack.of.feedback.documents
            '200413' => self::SENT, // package.not.delivered.refuse.feedback.documents.inconsistent.with.content
            '200414' => self::SENT, // package.not.delivered.refuse.parcel.demaged
            '200415' => self::SENT, // package.not.delivered.refuse.package.incomplete
            '200416' => self::SENT, // package.not.delivered.refuse
            '200500' => self::SENT, // package.not.delivered.receiver.negative.verification
            '200501' => self::SENT, // package.not.delivered.receiver.negative.verification
            '200502' => self::SENT, // package.not.delivered.receiver.negative.verification
            '200503' => self::SENT, // package.not.delivered.receiver.negative.verification
            '200504' => self::SENT, // package.not.delivered.receiver.negative.verification
            '200505' => self::SENT, // return.parcel.unprepared
            '200601' => self::NEW, // date.of.service.agreed.later
            '200602' => self::SENT, // personal.pick.up
            '200604' => self::NEW, // date.of.service.agreed.later
            '200700' => self::SENT, // package.not.delivered.refusal.with.siganture
            '200701' => self::SENT, // package.not.delivered.lack.of.cod.cash
            '200702' => self::SENT, // package.not.delivered.receiver.resigned
            '200703' => self::SENT, // package.not.delivered.telephone.resignation
            '200704' => self::SENT, // package.not.delivered.article.not.ordered
            '200705' => self::SENT, // package.not.delivered.cod.payment.refuse.bad.payment.type
            '200706' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '200708' => self::SENT, // package.not.delivered.refuse.lack.of.exw.cash
            '200709' => self::SENT, // package.not.delivered.refuse.bad.payment.type.exw
            '200710' => self::SENT, // package.not.delivered.refuse.incorrect.exw.amount
            '200711' => self::SENT, // package.not.delivered.refuse
            '200712' => self::SENT, // package.not.delivered.refuse.lack.of.feedback.documents
            '200713' => self::SENT, // package.not.delivered.refuse.feedback.documents.inconsistent.with.content
            '200714' => self::SENT, // package.not.delivered.refuse.parcel.demaged
            '200715' => self::SENT, // package.not.delivered.refuse.package.incomplete
            '200716' => self::SENT, // package.not.delivered.refuse
            '200801' => self::SENT, // not.delivered.drive.impossible
            '210109' => self::SENT, // package.not.delivered.incorrect.address
            '210111' => self::NEW, // date.of.service.agreed.later
            '230101' => self::SENT, // packge.stored.at.depot
            '230207' => self::SENT, // sorting.error
            '230208' => self::SENT, // sorting.error
            '230209' => self::SENT, // sorting.error
            '230210' => self::SENT, // sorting.error
            '230309' => self::SENT, // no.package.in.delivery.depot
            '230310' => self::ERROR, // package.discarded
            '230402' => self::REDIRECTED, // package.redirected.according.instruction
            '230403' => self::RETURNED, // package.returned
            '230506' => self::SENT, // package.waiting.for.pickup.timeout
            '250301' => self::NEW, // package.weight.changed
            '260403' => self::SENT, // sorting.error
            '320201' => self::SENT, // package.stored.at.depot
            '320202' => self::SENT, // package.repacked
            '330137' => self::SENT, // package.adoption.at.dpd.depot
            '500011' => self::SENT, // package.not.delivered.incorrect.address
            '500015' => self::SENT, // package.not.delivered.refusal
            '500019' => self::SENT, // package.not.delivered.receiver.absent
            '500037' => self::SENT, // package.not.delivered.receiver.absent
            '500042' => self::SENT, // package.not.delivered.receiver.absent
            '500046' => self::SENT, // package.not.delivered.lack.of.COD.cash
            '500085' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '500411' => self::SENT, // package.not.delivered.incorrect.address
            '500414' => self::SENT, // package.not.delivered.article.not.ordered
            '500415' => self::SENT, // package.not.delivered.refusal
            '500419' => self::SENT, // package.not.delivered.receiver.absent
            '500437' => self::SENT, // package.not.delivered.receiver.absent
            '500442' => self::SENT, // package.not.delivered.receiver.absent
            '500446' => self::SENT, // package.not.delivered.lack.of.COD.cash
            '500485' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '500610' => self::RETURNED, // package.returned
            '500611' => self::RETURNED, // package.returned
            '500612' => self::RETURNED, // package.returned
            '500614' => self::RETURNED, // package.returned
            '500615' => self::RETURNED, // package.returned
            '500616' => self::RETURNED, // package.returned
            '500617' => self::RETURNED, // package.returned
            '500624' => self::RETURNED, // package.returned
            '500629' => self::RETURNED, // package.returned
            '500630' => self::RETURNED, // package.returned
            '500633' => self::RETURNED, // package.returned
            '500635' => self::RETURNED, // package.returned
            '500637' => self::RETURNED, // package.returned
            '500639' => self::RETURNED, // package.returned
            '500642' => self::RETURNED, // package.returned
            '500649' => self::RETURNED, // package.returned
            '500661' => self::RETURNED, // package.returned
            '500684' => self::RETURNED, // package.returned
            '500685' => self::RETURNED, // package.returned
            '500811' => self::SENT, // package.not.delivered.incorrect.address
            '500885' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '501300' => self::DELIVERED, // package.delivered
            '501304' => self::DELIVERED, // package.delivered
            '501340' => self::DELIVERED, // package.delivered
            '502300' => self::SENT, // package.transferred.to.pickup.point
            '511201' => self::SENT, // package.adoption.at.pickup.point
            '511701' => self::SENT, // sent.notification
            '511702' => self::SENT, // sent.notification
            '511703' => self::SENT, // sent.notification
            '511704' => self::SENT, // sent.notification
            '511801' => self::SENT, // package.transferred.to.courier.by.pickup.point
            '511901' => self::SENT, // package.delivered.at.pickup.point
            '511902' => self::SENT, // package.with.return.parcel.delivered.at.pickup.point
            '511903' => self::SENT, // damaged.package.delivered.at.pickup.point
            '512001' => self::SENT, // package.damaged.delivered.at.pickup.point
            '600106' => self::SENT, // package.sent.from.pickup.point
            '600401' => self::SENT, // package.not.delivered.lack.of.cod.cash
            '600402' => self::SENT, // package.not.delivered.receiver.resigned
            '600403' => self::SENT, // package.not.delivered.receiver.resigned
            '600404' => self::SENT, // package.not.delivered.article.not.ordered
            '600405' => self::SENT, // package.not.delivered.cod.payment.refuse.bad.payment.type
            '600406' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '600411' => self::SENT, // package.not.delivered.at.pickup.point.refusal.late.delivery
            '600412' => self::SENT, // package.not.delivered.refuse.lack.of.feedback.documents
            '600413' => self::SENT, // package.not.delivered.refuse.feedback.documents.inconsistent.with.content
            '600414' => self::SENT, // package.not.delivered.refuse.parcel.demaged
            '600415' => self::SENT, // package.not.delivered.refuse.package.incomplete
            '600416' => self::SENT, // package.not.delivered.refuse
            '600501' => self::SENT, // package.not.delivered.receiver.negative.verification
            '600502' => self::SENT, // package.not.delivered.receiver.negative.verification
            '600503' => self::SENT, // package.not.delivered.refusal
            '600504' => self::SENT, // package.not.delivered.receiver.negative.verification
            '600505' => self::SENT, // package.not.delivered.at.pickup.point.lack.of.feedback.parcel
            '600601' => self::NEW, // date.of.service.agreed.later
            '600602' => self::SENT, // package.not.delivered.pickup.at.pickup.point.agreed
            '600701' => self::SENT, // package.not.delivered.lack.of.cod.cash
            '600702' => self::SENT, // package.not.delivered.refusal.with.siganture
            '600703' => self::SENT, // package.not.delivered.refusal.with.siganture
            '600704' => self::SENT, // package.not.delivered.article.not.ordered
            '600705' => self::SENT, // package.not.delivered.cod.payment.refuse.bad.payment.type
            '600706' => self::SENT, // package.not.delivered.cod.payment.refuse.incorect.amount
            '600711' => self::SENT, // package.not.delivered.refusal
            '600712' => self::SENT, // package.not.delivered.refuse.lack.of.feedback.documents
            '600713' => self::SENT, // package.not.delivered.refuse.feedback.documents.inconsistent.with.content
            '600714' => self::SENT, // package.not.delivered.refuse.parcel.demaged
            '600715' => self::SENT, // package.not.delivered.refuse.package.incomplete
            '600716' => self::SENT, // package.not.delivered.refusal.with.siganture
            '700401' => self::SENT, // package.sent.from.pickup.point
            '701701' => self::SENT, // package.handed.to.courier.by.pickup.point
            '701901' => self::DELIVERED, // package.delivered.by.pickup.point
            '701902' => self::SENT, // package.with.return.parcel.delivered.by.pickup.point
            '702001' => self::SENT, // package.not.delivered.refusal.at.pickup.point
            '703201' => self::SENT, // parcel.stored.at.pickup.point
            '040101' => self::SENT, // package.received.by.courier
            '040102' => self::SENT, // package.received.by.courier
            '040501' => self::NEW, // collection.request.postponed.sender.absent
            '040502' => self::NEW, // collection.request.postponed.notime
            '040503' => self::NEW, // collection.request.postponed.parcel.not.ready
            '040504' => self::NEW, // collection.request.postponed.lack.of.customs.documents
            '040505' => self::NEW, // collection.request.postponed.too.many.parcels
            '040601' => self::NEW, // collection.request.canceled.sender.not.informed
            '040602' => self::NEW, // collection.request.canceled.package.already.sent
            '040603' => self::NEW, // collection.request.canceled.incorrect.address
            '040604' => self::NEW, // collection.request.canceled.fewer.parcels.than.in.order
            '040605' => self::NEW, // collection.request.canceled.incorrect.weight.dimension
            '050101' => self::SENT, // package.adoption.at.sort.area
            '050102' => self::SENT, // package.adoption.at.sort.area
            '123456' => self::SENT, // ¯\_(ツ)_/¯
            '230405' => self::SENT, // sent.outside.poland
            '240304' => self::SENT, // returndoc.send.to.sender
            '330135' => self::SENT, // package.adoption.at.dpd.depot
            '370101' => self::SENT, // package.adoption.at.dpd.depot
            '370201' => self::SENT, // package.not.transferred.to.pickup.point
            '370202' => self::SENT, // package.not.transferred.to.pickup.point
            '370203' => self::SENT, // package.not.transferred.to.pickup.point
            '370204' => self::SENT, // package.not.transferred.to.pickup.point
            '370205' => self::SENT, // package.not.transferred.to.pickup.point
            '370206' => self::SENT, // package.not.transferred.to.pickup.point
            '410135' => self::SENT, // package.adoption.from.courier
            '600101' => self::SENT, // package.adoption.at.pickup.point
            '600102' => self::SENT, // package.adoption.at.dpd.depot
            '600103' => self::SENT, // package.delivered.at.pickup.point
            '600104' => self::SENT, // package.with.return.parcel.delivered.at.pickup.point
            '600105' => self::SENT, // package.sent.to.DPDdepot
            '654321' => self::NEW, // collection.request.assigned.to.courier
            '702102' => self::SENT, // package.not.delivered.receiver.negative.verification
            '030103' => self::NEW, // registered.parcel.data
            '220101' => self::SENT, // package.adoption.at.dpd.depot
        ];
    }

    public static function delivered(): array
    {
        return \array_filter(self::all(),
            function ($status) {
                return $status === self::DELIVERED;
            }
        );
    }

    public static function redirected(): array
    {
        return \array_filter(self::all(),
            function ($status) {
                return $status === self::REDIRECTED;
            }
        );
    }

    public static function mapBusinessCodeToStatus(string $businessCode): ?string
    {
        return self::all()[$businessCode] ?? null;
    }
}
