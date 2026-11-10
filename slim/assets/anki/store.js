// 固定のデモカード。形は要件のデータモデル（anki_cards）に合わせる
// 段取り3でサーバーからの取得に替える
const DEMO_CARDS = [
  {
    uuid: '00000000-0000-4000-8000-000000000001',
    front_md: 'apple',
    back_md: 'りんご',
    front_size: 'large', back_size: 'large',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['英単語'],
  },
  {
    uuid: '00000000-0000-4000-8000-000000000002',
    front_md: '光の速さは秒速およそ何 km？',
    back_md: '約30万 km\n（299,792.458 km/s）',
    front_size: 'large', back_size: 'large',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['一般常識'],
  },
  {
    uuid: '00000000-0000-4000-8000-000000000003',
    front_md: '「温故知新」の意味',
    back_md: '昔のことを調べて、そこから新しい知識や見解を得ること。\n出典は『論語』為政篇。',
    front_size: 'large', back_size: 'normal',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['一般常識'],
  },
  {
    uuid: '00000000-0000-4000-8000-000000000004',
    front_md: 'HTTP ステータスコード 404',
    back_md: 'Not Found\nリクエストしたリソースが見つからない',
    front_size: 'large', back_size: 'large',
    f2b_status: 'weak', b2f_status: 'none',
    tags: ['IT'],
  },
  {
    // 長文（裏が画面に収まらない）
    uuid: '00000000-0000-4000-8000-000000000005',
    front_md: 'JavaScript の配列のメソッドを7つ挙げ、それぞれ説明せよ',
    back_md: [
      'map: 各要素を変換した、新しい配列を返す',
      '  [1, 2, 3].map((n) => n * 2)  // [2, 4, 6]',
      '',
      'filter: 条件に合う要素だけの、新しい配列を返す',
      '  [1, 2, 3].filter((n) => n > 1)  // [2, 3]',
      '',
      'find: 条件に合う最初の要素を返す。なければ undefined',
      '  [1, 2, 3].find((n) => n > 1)  // 2',
      '',
      'some: 条件に合う要素が1つでもあれば true',
      '  [1, 2, 3].some((n) => n > 2)  // true',
      '',
      'every: すべての要素が条件に合えば true',
      '  [1, 2, 3].every((n) => n > 2)  // false',
      '',
      'reduce: 要素を順にたたみ込んで、1つの値にする',
      '  [1, 2, 3].reduce((sum, n) => sum + n, 0)  // 6',
      '',
      'forEach: 各要素に処理を行う。戻り値はない',
      '  [1, 2, 3].forEach((n) => console.log(n))',
    ].join('\n'),
    front_size: 'large', back_size: 'normal',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['IT'],
  },
  {
    uuid: '00000000-0000-4000-8000-000000000006',
    front_md: '日本で一番高い山',
    back_md: '富士山（3,776 m）',
    front_size: 'large', back_size: 'large',
    f2b_status: 'memorized', b2f_status: 'none',
    tags: ['一般常識'],
  },
  {
    // 情報量が多いので、裏を small にする
    uuid: '00000000-0000-4000-8000-000000000007',
    front_md: 'Fisher–Yates シャッフルの手順',
    back_md: [
      '1. 配列の末尾の位置を i とする',
      '2. 0 以上 i 以下の整数 j をランダムに選ぶ',
      '3. i 番目と j 番目の要素を入れ替える',
      '4. i を1つ減らし、i が 1 になるまで 2〜3 を繰り返す',
      '',
      'どの並びも同じ確率で出る。計算量は O(n)',
    ].join('\n'),
    front_size: 'large', back_size: 'small',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['IT'],
  },
  {
    uuid: '00000000-0000-4000-8000-000000000008',
    front_md: 'ephemeral',
    back_md: '一時的な、はかない',
    front_size: 'large', back_size: 'large',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['英単語'],
  },
  {
    // 長文（表が画面に収まらないことがある）
    uuid: '00000000-0000-4000-8000-000000000009',
    front_md: [
      '次の月を英語で書け',
      '',
      '1月', '2月', '3月', '4月', '5月', '6月',
      '7月', '8月', '9月', '10月', '11月', '12月',
    ].join('\n'),
    back_md: [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December',
    ].join('\n'),
    front_size: 'large', back_size: 'large',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['英単語'],
  },
  {
    uuid: '00000000-0000-4000-8000-000000000010',
    front_md: 'CSS で、要素を親の縦中央に置く方法（flex の中で）',
    back_md: 'margin-block: auto',
    front_size: 'normal', back_size: 'large',
    f2b_status: 'none', b2f_status: 'none',
    tags: ['IT'],
  },
]

// 束の全カードを返す
// 呼び出し側でカードを書き換えても固定のデータが変わらないよう、複製して渡す
export const loadCards = async () => structuredClone(DEMO_CARDS)

// カードの状態（ none / memorized / weak ）を変える
// directionはf2bかb2f
export const setStatus = (card, direction, status) => {
  card[`${direction}_status`] = status
}
