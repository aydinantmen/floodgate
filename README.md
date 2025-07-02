# Floodgate
Prevents http-based syn flood and land flood attacks

TANIM:

1- HTTP Bağlantı Prensibi

HTTP protokolünde çalışan bir sisteme bağlanmadan önce sunucu ve istemci arasında üçlü tokalaşma adı verilen bir veri akışı gerçekleşir.
Önce normal bir bağlantının nasıl kurulduğunu inceleyelim.

SYN
İstemci, (bilgisayarımız) sunucuya bağlantı kurmak istediğini belirten bir SYN (Synchronize) yani senkronizasyon paketi gönderir. Bağlantı kurulabilmesi için istemci ve sunucu birbiriyle uyumlu olmalıdır. SYN paketi istemci yapısı hakkında bilgi taşımaktadır.

SYN + ACK
Sunucu, SYN paketini aldığında istemcinin yapısını analiz ederek bağlantının kurulabileceğini belirten bir ACK (Acknowledge) yani bilgilendirme paketi ve sunucu yapısı hakkında bilgi taşıyan bir SYN paketi göndererek istemcinin yanıtını beklemeye başlar.

ACK
İstemci, SYN + ACK paketini aldığında sunucunun yapısını analiz eder ve bağlantının kurulabilmesi için gereken koşulları sağladığını belirten son bir ACK paketi gönderir ve sunucu ile istemci arasında bağlantı kurularak veri transferi başlamış olur.

DATA TRANSFER

2- HTTP Flood Saldırısı

Buraya kadar normal bir HTTP bağlantısının kurulmasını inceledik.
Şimdi HTTP Flood saldırısının nasıl gerçekleştiğini inceleyelim.

SYN
İstemci, normal bağlantı talebinde olduğu gibi bir SYN paketi göndererek bağlantı kurma talebinde bulunur.

SYN + ACK
Sunucu, normal bağlantı talebinde olduğu gibi SYN + ACK paketini gönderir ve istemcinin yanıtını bekler.

SYN+1
İstemci, son ACK paketini göndermez ve sunucu yanıt beklerken yeni bir SYN ile süreci tekrarlar.

3- Koruma Prensibi
Normal bağlantı prensibini ve bu prensiplerin manipüle edilerek nasıl saldırı yapıldığını gördük.
Son olarak güvenliği nasıl sağladığımızı görelim.

ALGILAMA
HTTP Flood ve türevi saldırılar DDOS gibi sunucu katmanında değil, uygulama katmanında gerçekleşir. Bu sayede bağlantı parametrelerini inceleyerek saldırıyı saldırı esnasında algılayan özel bir algoritma geliştirdik.

AYIRT ETME
Saldırı devam ederken normal bağlantı talepleri gelmeye devam edebilir normal bir sunucunun burada yanıtsız kalmaması gerekmektedir. Yazılımımız bu noktada saldırı talepleri ile normal bağlantı taleplerini birbirinden ayırır.

ENGELLEME
Saldırı saniyeler içinde algılanır ve saldırı kaynağı, sunucu yeni bağlantı taleplerine hala yanıt verebilir durumdayken sunucu katmanına bildirilir. Bu sayede saldırı kaynağı sunucu katmanında engellenir ve sunucu saldırıdan etkilenmez.
